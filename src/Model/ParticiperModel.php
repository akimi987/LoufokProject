<?php

namespace App\Model;

class ParticiperModel extends Model
{
    protected $tableName = 'participer';
    protected $tableContributionName = 'contribution';
    protected static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getNombreContributions(int $idCadavre): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->tableContributionName} WHERE id_cadavre = :idCadavre";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':idCadavre', $idCadavre);
        $sth->execute();

        return (int) $sth->fetchColumn();
    }
    public function attribuerNumeroAleatoire(int $idJoueur, int $idCadavre): void
    {
        // Récupérer le nombre de contributions existantes pour ce cadavre
        $nombreContributions = $this->getNombreContributions($idCadavre);

        // Générer un numéro aléatoire basé sur le nombre de contributions
        $numAleatoire = rand(1, $nombreContributions + 1);

        // Insérer le numéro aléatoire dans la table participer
        $sql = "INSERT INTO {$this->tableName} (id_joueur, id_cadavre, num_contribution) VALUES (:idJoueur, :idCadavre, :numAleatoire)";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':idJoueur', $idJoueur);
        $sth->bindParam(':idCadavre', $idCadavre);
        $sth->bindParam(':numAleatoire', $numAleatoire);
        $sth->execute();
    }

    public function getNumContribution(int $idJoueur, int $idCadavre): ?int
    {
        $sql = "SELECT num_contribution FROM {$this->tableName} WHERE id_joueur = :idJoueur AND id_cadavre = :idCadavre";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':idJoueur', $idJoueur);
        $sth->bindParam(':idCadavre', $idCadavre);
        $sth->execute();

        return $sth->fetchColumn();
    }

    public function joueurAContribue(int $idJoueur, int $idCadavre): bool
    {
        $sql = "SELECT COUNT(*) FROM {$this->tableName} WHERE id_joueur = :idJoueur AND id_cadavre = :idCadavre";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':idJoueur', $idJoueur);
        $sth->bindParam(':idCadavre', $idCadavre);
        $sth->execute();

        return ($sth->fetchColumn() > 0);
    }


    public function getJoueurContribution(int $idJoueur, int $idCadavre): ?array
    {
        $sql = "SELECT c.texte_contribution
                FROM {$this->tableName} p
                JOIN {$this->tableContributionName} c ON p.id_cadavre = c.id_cadavre AND p.num_contribution = c.ordre_soumission
                WHERE p.id_joueur = :idJoueur AND p.id_cadavre = :idCadavre";

        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':idJoueur', $idJoueur);
        $sth->bindParam(':idCadavre', $idCadavre);
        $sth->execute();

        $result = $sth->fetch();

        return $result ? [$result['texte_contribution']] : null;
    }
}
