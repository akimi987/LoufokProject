<?php

namespace App\Model;

class CadavreModel extends Model
{
    protected $tableName = 'cadavre';
    protected $tableContributionName = 'contribution';

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
        $sth = $this->query($sql, [':currentDate' => $currentDate]);

        if ($sth && $sth->rowCount() > 0) {
            return $sth->fetch();
        }

        return null;
    }

    public function findAllContributions($idCadavre)
    {
        $sql = "SELECT * FROM {$this->tableContributionName} WHERE id_cadavre = :idCadavre";
        $sth = $this->query($sql, [':idCadavre' => $idCadavre]);

        return $sth ? $sth->fetchAll() : [];
    }

    public function findAllContributionsHideExcept($idCadavre, $idContrib1, $idContrib2)
    {
        $sql = "SELECT * FROM {$this->tableContributionName} WHERE id_cadavre = :idCadavre AND id_contribution != :idContrib1 AND id_contribution != :idContrib2";
        $sth = $this->query($sql, [':idCadavre' => $idCadavre, ':idContrib1' => $idContrib1, ':idContrib2' => $idContrib2]);

        return $sth ? $sth->fetchAll() : [];
    }

    public function findAllContributionHideExcept($idCadavre, $idContrib1)
    {
        $sql = "SELECT * FROM {$this->tableContributionName} WHERE id_cadavre = :idCadavre AND id_contribution != :idContrib1";
        $sth = $this->query($sql, [':idCadavre' => $idCadavre, ':idContrib1' => $idContrib1]);

        return $sth ? $sth->fetchAll() : [];
    }

    public function addContribution($text, $idCadavre, $idGamer)
    {
        $sql = "INSERT INTO {$this->tableContributionName} (texte_contribution, id_cadavre, id_administrateur) VALUES (:text, :idCadavre, :idGamer)";
        $sth = $this->query($sql, [':text' => $text, ':idCadavre' => $idCadavre, ':idGamer' => $idGamer]);

        return $sth ? true : false;
    }
}