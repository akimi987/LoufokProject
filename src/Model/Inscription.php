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

    public function registerUser(array $userData): void
    {
        // Ajoutez ici la logique pour enregistrer l'utilisateur dans la base de données
        // Assurez-vous de sécuriser le mot de passe avant de le stocker

        // Exemple :


        $this->create([
            'nom_plume' => $userData['nom_plume'],
            'ad_mail_joueur' => $userData['ad_mail_joueur'],
            'mot_de_passe_joueur' => $userData['mot_de_passe_joueur'],
            'sexe' => $userData['sexe'],
            'ddn' => $userData['ddn'],
        ]);
    }
}
