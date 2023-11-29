<?php

namespace App\Model;

class CadavreModel extends Model
{
    protected $tableName = 'cadavre';
    protected $tableContributionName = 'contribution';
    protected static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function createCadavre(array $cadavreData, string $premiereContribution, int $idAdministrateur): ?int
    {
        $validationErrors = $this->validateCadavreData($cadavreData);

        if (!empty($validationErrors)) {
            return null;
        }

        try {
            $cadavreId = parent::create($cadavreData);
            Contribution::getInstance()->create([
                'texte_contribution' => $premiereContribution,
                'date_soumission' => date('Y-m-d H:i:s'),
                'ordre_soumission' => 1,
                'id_administrateur' => $idAdministrateur,
                'id_cadavre' => $cadavreId,
            ]);
            return $cadavreId;
        } catch (\Exception $e) {

            return null;
        }
    }
    public function addContribution($texte, $idCadavre, $idGamer)
    {
        $ordreSoumission = $this->calculerOrdreSoumission($idCadavre);
        $date = date('Y-m-d H:i:s');
        Contribution::getInstance()->create([
            'texte_contribution' => trim($_POST['premiereContribution']),
            'date_soumission' => $date,
            'ordre_soumission' => $ordreSoumission,
            'id_joueur' => $idGamer,
            'id_cadavre' => $idCadavre,
        ]);
    }

    public function getCadavreEnCours()
    {
        $currentDate = date('Y-m-d H:i:s');

        $sql = "SELECT titre_cadavre, date_debut_cadavre, date_fin_cadavre, nb_contributions
            FROM cadavre
            WHERE date_fin_cadavre >= :currentDate
            ORDER BY date_debut_cadavre ASC
            LIMIT 1";

        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':currentDate', $currentDate);
        $sth->execute();

        return $sth->fetch();
    }
    private function calculerOrdreSoumission($idCadavre)
    {
        $sql = "SELECT COUNT(*) FROM {$this->tableContributionName} WHERE id_cadavre = :idCadavre";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':idCadavre', $idCadavre);
        $sth->execute();

        $nombreContributions = $sth->fetchColumn();
        return $nombreContributions + 1;
    }
    public function isTitleExists(string $title): bool
    {
        $sql = "SELECT COUNT(*) FROM {$this->tableName} WHERE titre_cadavre = :title";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':title', $title);
        $sth->execute();

        return ($sth->fetchColumn() > 0);
    }

    public function hasOverlap(string $startDate, string $endDate): bool
    {
        $sql = "SELECT COUNT(*) FROM {$this->tableName} 
                WHERE (date_debut_cadavre < :endDate AND date_fin_cadavre > :startDate)
                OR (date_debut_cadavre < :startDate AND date_fin_cadavre > :startDate)";

        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':startDate', $startDate);
        $sth->bindParam(':endDate', $endDate);
        $sth->execute();

        return ($sth->fetchColumn() > 0);
    }
    public function validateCadavreData(array $cadavreData): array
    {
        $errors = [];
        if (empty($cadavreData['titre_cadavre'])) {
            $errors[] = "Le titre du cadavre est requis.";
        } elseif ($this->isTitleExists($cadavreData['titre_cadavre'])) {
            $errors[] = "Le titre du cadavre existe déjà. Choisissez un titre unique.";
        }
        $dateDebut = strtotime($cadavreData['date_debut_cadavre']);
        $dateFin = strtotime($cadavreData['date_fin_cadavre']);

        if ($dateDebut === false || $dateFin === false || $dateFin <= $dateDebut) {
            $errors[] = "Veuillez choisir des dates valides. La date de fin doit être supérieure à la date de début.";
        }
        if (!is_numeric($cadavreData['nb_contributions']) || $cadavreData['nb_contributions'] < 1) {
            $errors[] = "Le nombre max de contributions doit être un nombre entier supérieur ou égal à 1.";
        }
        return $errors;
    }
    public function getCadavreInfos($id)
    {
        $sql = "SELECT titre_cadavre, date_debut_cadavre, date_fin_cadavre, nb_contributions
        FROM cadavre
        WHERE id_cadavre = :id";

        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':id', $id);
        $sth->execute();

        return $sth->fetch();
    }
}
