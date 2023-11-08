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
        $sth->bindParam(':id', $idCadavre);
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

    public function addContribution($text, $idCadavre, $idGamer)
    {
        self::$dbh->beginTransaction();

        try {
            $sql = "INSERT INTO {$this->tableContributionName} (texte_contribution, id_cadavre, id_administrateur) VALUES (:text, :idCadavre, :idGamer)";
            $sth = $this->query($sql, [':text' => $text, ':idCadavre' => $idCadavre, ':idGamer' => $idGamer]);

            //  On valide la transaction
            if ($sth) {
                self::$dbh->commit();
                return true;
            } else {
                // On annule la transaction
                self::$dbh->rollBack();
                return false;
            }
        } catch (\Exception $e) {
            self::$dbh->rollBack();
            throw $e;
        }
    }
}
