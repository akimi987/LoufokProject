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

    public function demarrerCadavre($cadavreTitre, $premiereContribution, $dateDebut, $dateFin, $maxContributions, $idAdmin)
    {
/*         if ($this->isCadavreEnCours(1)) {
        }
        if ($maxContributions <= 1) {
        }
        if (strlen(($premiereContribution) < 50 || strlen($premiereContribution) > 280)) {
        } */
        $sql = "INSERT INTO {$this->tableName} (titre_cadavre, date_debut_cadavre, date_fin_cadavre, nb_contributions) VALUES (:titre, :date_Debut, :date_Fin, :max_contributions)";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':titre', $cadavreTitre);
        $sth->bindParam(':date_Debut', $dateDebut);
        $sth->bindParam(':date_Fin', $dateFin);
        $sth->bindParam(':max_contributions', $maxContributions);
        $sth->execute();
        $cadavreId = self::$dbh->lastInsertId();
        $this->soumettrePremiereContribution($premiereContribution, $cadavreId, $idAdmin);

        return $cadavreId;
    }

    private function isCadavreEnCours()
    {
        $sql = "SELECT COUNT(*) FROM {$this->tableName} WHERE NOW() BETWEEN date_debut_cadavre AND date_fin_cadavre";
        $sth = self::$dbh->prepare($sql);
        $sth->execute();
        $nombreCadavresEnCours = $sth->fetchColumn();

        return ($nombreCadavresEnCours > 0);
    }

    private function soumettrePremiereContribution($texte, $idCadavre, $idGamer)
    {
        $ordreSoumission = $this->calculerOrdreSoumission($idCadavre);
        $sql = "INSERT INTO {$this->tableContributionName} (texte_contribution, date_soumission, ordre_soumission, id_administrateur, id_cadavre) VALUES (:texte, :dateSoumission, :ordreSoumission, :idAdmin, :idCadavre)";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':texte', $texte);
        $sth->bindParam(':dateSoumission', date('Y-m-d H:i:s'));
        $sth->bindParam(':ordreSoumission', $ordreSoumission);
        $sth->bindParam(':idAdmin', $idGamer);
        $sth->bindParam(':idCadavre', $idCadavre);
        $sth->execute();
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
    public function getLastInsertedId()
    {
        return $this->dbh->lastInsertId();
    }

    public function findAllContributionsHideExcept($idCadavre, $idContrib1, $idContrib2)
    {
        $sql = "SELECT * FROM {$this->tableContributionName} WHERE id_cadavre = :idCadavre AND id_contribution != :idContrib1 AND id_contribution != :idContrib2";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':idCadavre', $idCadavre);
        $sth->bindParam(':idContrib1', $idContrib1);
        $sth->bindParam(':idContrib1', $idContrib2);
        $sth->execute();
        return $sth ? $sth->fetchAll() : [];
    }

    public function findCurrent()
    {
        $currentDate = date('Y-m-d H:i:s');
        $sql = "SELECT * FROM {$this->tableName} WHERE date_debut_cadavre <= :currentDate AND date_fin_cadavre >= :currentDate";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':currentDate', $currentDate);
        $sth->execute();
        if ($sth && $sth->rowCount() > 0) {
            return $sth->fetch();
        }

        return null;
    }
    public function getInfos($id)
    {
        $sql = "SELECT titre_cadavre, date_debut_cadavre, date_fin_cadavre, nb_contributions, (SELECT COUNT(*) FROM contribution WHERE id_cadavre = c.id_cadavre) AS nb_contributions_actuel FROM {$this->tableName} c WHERE id_cadavre = :id";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':id', $id);
        $sth->execute();

        return $sth ? $sth->fetch() : null;
    }

    public function getCadavreEnCoursId()
    {
        $currentDate = date('Y-m-d H:i:s');
        $sql = "SELECT id_cadavre FROM {$this->tableName} WHERE date_debut_cadavre <= :currentDate AND date_fin_cadavre >= :currentDate";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':currentDate', $currentDate);
        $sth->execute();

        if ($sth && $sth->rowCount() > 0) {
            $result = $sth->fetch();
            return $result['id_cadavre'];
        }

        return null;
    }

    public function findAllContributions($idCadavre)
    {
        $sql = "SELECT * FROM {$this->tableContributionName} WHERE id_cadavre = :idCadavre";

        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':id', $idCadavre);
        $sth->execute();

        return $sth ? $sth->fetchAll() : [];
    }

    public function findAllContributionHideExcept($idCadavre, $idContrib1)
    {
        $sql = "SELECT * FROM {$this->tableContributionName} WHERE id_cadavre = :idCadavre AND id_contribution != :idContrib1";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':idCadavre', $idCadavre);
        $sth->bindParam(':idContrib1', $idContrib1);
        $sth->execute();

        return $sth ? $sth->fetchAll() : [];
    }

    private function isTitreUnique($titre)
    {
        $sql = "SELECT COUNT(*) FROM {$this->tableName} WHERE titre_cadavre = :titre";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':titre', $titre);
        $sth->execute();
        $count = $sth->fetchColumn();

        return $count === 0;
    }
}