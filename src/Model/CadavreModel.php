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

    public function findAllContributions($idCadavre)
    {
        $sql = "SELECT * FROM {$this->tableContributionName} WHERE id_cadavre = :idCadavre";

        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':idCadavre', $idCadavre);
        $sth->execute();

        return $sth ? $sth->fetchAll() : [];
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

    public function findAllContributionHideExcept($idCadavre, $idContrib1)
    {
        $sql = "SELECT * FROM {$this->tableContributionName} WHERE id_cadavre = :idCadavre AND id_contribution != :idContrib1";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':idCadavre', $idCadavre);
        $sth->bindParam(':idContrib1', $idContrib1);
        $sth->execute();

        return $sth ? $sth->fetchAll() : [];
    }

    public function addContribution($texte, $idCadavre, $idGamer)
    {
        self::$dbh->beginTransaction();
        $ordreSoumission = $this->calculerOrdreSoumission($idCadavre);
        try {
            $sql = "INSERT INTO {$this->tableContributionName} (texte_contribution, date_soumission, ordre_soumission, id_administrateur, id_cadavre) VALUES (:texte, :dateSoumission, :ordreSoumission, :idAdmin, :idCadavre)";
            $sth = self::$dbh->prepare($sql);
            $sth->bindParam(':texte', $texte);
            $sth->bindParam(':dateSoumission', date('Y-m-d H:i:s'));
            $sth->bindParam(':ordreSoumission', $ordreSoumission);
            $sth->bindParam(':idAdmin', $idGamer);
            $sth->bindParam(':idCadavre', $idCadavre);

            return $sth->execute();
            if ($sth) {
                self::$dbh->commit();
                return true;
            } else {
                self::$dbh->rollBack();
                return false;
            }
        } catch (\Exception $e) {
            self::$dbh->rollBack();
            throw $e;
        }
    }

    public function createCadavre($titre, $dateDebut, $dateFin, $nbContributions)
    {
        self::$dbh->beginTransaction();

        try {
            if ($this->isPeriodOverlap($dateDebut, $dateFin)) {
                throw new \Exception('La période chevauche une période existante.');
            }
            if ($this->isTitreExist($titre)) {
                throw new \Exception('Un cadavre avec le même titre existe déjà.');
            }

            $sql = "INSERT INTO {$this->tableName} (titre_cadavre, date_debut_cadavre, date_fin_cadavre, nb_contributions) VALUES (:titre, :dateDebut, :dateFin, :nbContributions)";
            $sth = self::$dbh->prepare($sql);
            $sth->bindParam(':titre', $titre);
            $sth->bindParam(':dateDebut', $dateDebut);
            $sth->bindParam(':dateFin', $dateFin);
            $sth->bindParam(':nbContributionsMax', $nbContributions);

            if (!$sth->execute()) {
                throw new \Exception('Erreur lors de l\'insertion du cadavre.');
            }

            $cadavreId = self::$dbh->lastInsertId();

            self::$dbh->commit();

            return $cadavreId;
        } catch (\Exception $e) {
            self::$dbh->rollBack();
            throw $e;
        }
    }

    private function isPeriodOverlap($dateDebut, $dateFin)
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE (date_debut_cadavre BETWEEN :dateDebut AND :dateFin) OR (date_fin_cadavre BETWEEN :dateDebut AND :dateFin)";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':dateDebut', $dateDebut);
        $sth->bindParam(':dateFin', $dateFin);
        $sth->execute();

        return $sth->rowCount() > 0;
    }

    private function isTitreExist($titre)
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE titre_cadavre = :titre";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':titre', $titre);
        $sth->execute();

        return $sth->rowCount() > 0;
    }

    public function findById($cadavreId)
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE id = :cadavreId";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':cadavreId', $cadavreId);
        $sth->execute();

        return $sth ? $sth->fetch() : null;
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
}
