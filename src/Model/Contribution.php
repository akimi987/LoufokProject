<?php

namespace App\Model;

class Contribution extends Model
{
    protected $tableName = 'contribution';
    protected static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getMaskedContributions(int $idCadavre): array
    {
        $sql = "SELECT ordre_soumission FROM {$this->tableName} WHERE id_cadavre = :idCadavre ORDER BY ordre_soumission";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':idCadavre', $idCadavre);
        $sth->execute();

        $maskedContributions = [];
        while ($ordreSoumission = $sth->fetchColumn()) {
            $maskedContributions[] = str_repeat('_', 15) . '<br>' . str_repeat('_', 15);
        }

        return $maskedContributions;
    }


    public function getAllContributionsForCadavre($idCadavre)
    {
        $sql = "SELECT * FROM contribution WHERE id_cadavre = :idCadavre ORDER BY ordre_soumission";

        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':idCadavre', $idCadavre);
        $sth->execute();

        return $sth ? $sth->fetchAll() : [];
    }


    public function getRandomContribution(int $idCadavre, int $numContribution): ?string
    {
        $sql = "SELECT texte_contribution FROM {$this->tableName} WHERE id_cadavre = :idCadavre AND ordre_soumission = :numContribution";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':idCadavre', $idCadavre);
        $sth->bindParam(':numContribution', $numContribution);
        $sth->execute();

        return $sth->fetchColumn();
    }

    public function attribuerContributionAleatoire($idJoueur, $idCadavre)
    {
        $numContributionAleatoire = $this->getNumContributionAleatoire($idJoueur, $idCadavre);
        if (!$numContributionAleatoire) {
            $totalContributions = $this->getTotalContributions($idCadavre);
            $numContributionAleatoire = mt_rand(1, $totalContributions + 1);
            $sql = "UPDATE participer
                    SET num_contribution = :numContributionAleatoire
                    WHERE id_joueur = :idJoueur AND id_cadavre = :idCadavre";
            $sth = self::$dbh->prepare($sql);
            $sth->bindParam(':numContributionAleatoire', $numContributionAleatoire);
            $sth->bindParam(':idJoueur', $idJoueur);
            $sth->bindParam(':idCadavre', $idCadavre);

            if ($sth->execute()) {
                return $numContributionAleatoire;
            } else {
                return false;
            }
        }

        return $numContributionAleatoire;
    }

    public function getNumContributionAleatoire($idJoueur, $idCadavre)
    {
        $sql = "SELECT num_contribution FROM participer WHERE id_joueur = :idJoueur AND id_cadavre = :idCadavre";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':idJoueur', $idJoueur);
        $sth->bindParam(':idCadavre', $idCadavre);
        $sth->execute();

        $result = $sth->fetch();

        if ($result) {
            return $result['num_contribution'];
        } else {
            return false;
        }
    }

    public function getTotalContributions($idCadavre)
    {
        $sql = "SELECT COUNT(*) as total_contributions FROM participer WHERE id_cadavre = :idCadavre";
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':idCadavre', $idCadavre);
        $sth->execute();

        $result = $sth->fetch();

        if ($result) {
            return $result['total_contributions'];
        } else {
            return false;
        }
    }
}
