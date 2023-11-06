<?php

namespace App\Model;

class Joueur extends Model
{
    protected $tableName = APP_TABLE_PREFIX . 'joueur';
    protected $tableContributionName =  APP_TABLE_PREFIX . 'contribution';
    protected $tableContributionAleatoire = APP_TABLE_PREFIX . 'soumettre';

    protected static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function findContributionRandom($idCadavre, $idGamer)
    {
        $sql = "SELECT * FROM `$this->tableContributionAleatoire` WHERE id_cadavre = :idCadavre AND id_joueur = :idGamer";

        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':idCadavre', $idCadavre);
        $sth->bindParam(':idGamer', $idGamer);
        $sth->execute();

        return $sth->fetch();
    }

    public function findHisContribution($idCadavre, $idGamer)
    {
        $sql = "SELECT * FROM `$this->tableContributionName` WHERE id_cadavre = :idCadavre AND id_joueur = :idGamer";

        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':idCadavre', $idCadavre);
        $sth->bindParam(':idGamer', $idGamer);
        $sth->execute();

        return $sth->fetch();
    }

    public function addContribution($text, $idCadavre, $idGamer)
    {
        $sql = "INSERT INTO `$this->tableContributionName` (id_contribution, texte_contribution, date_soumission, id_cadavre, id_joueur)
        VALUES (null, :text, NOW(), :idCadavre, :idGamer)";
        $sth = $this->query($sql, [':text' => $text, ':idCadavre' => $idCadavre, ':idGamer' => $idGamer]);

        if ($sth) {
            return self::$dbh->lastInsertId();
        }

        return null;
    }
}
