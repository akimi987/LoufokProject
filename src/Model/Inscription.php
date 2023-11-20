<?php

namespace App\Model;

class Inscription extends Model
{
    protected $tableName = APP_TABLE_PREFIX . 'joueur';

    protected static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function registerUser($userData)
    {
        // Hasher le mot de passe avant de l'insérer dans la base de données
        //$hashedPassword = password_hash($userData['mot_de_passe_joueur'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO $this->tableName (nom_plume, ad_mail_joueur, mot_de_passe_joueur, sexe, ddn) 
                VALUES (:nom_plume, :ad_mail_joueur, :mot_de_passe_joueur, :sexe, :ddn)";
        $sth = self::$dbh->prepare($sql);

        $sth->bindParam(':nom_plume', $userData['nom_plume']);
        $sth->bindParam(':ad_mail_joueur', $userData['ad_mail_joueur']);
        $sth->bindParam(':mot_de_passe_joueur', $hashedPassword);
        $sth->bindParam(':sexe', $userData['sexe']);
        $sth->bindParam(':ddn', $userData['ddn']);

        //var_dump($userData);

        $sth->execute();
    }
}
