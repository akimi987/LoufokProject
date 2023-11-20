<?php

namespace App\Controller;

use App\Helper\HTTP;
use App\Model\Inscription;

class InscriptionController extends Controller
{
    public function index()
    {
        $this->display('inscription/inscription.html.twig');
    }

    public function register()
    {
        $userData = [
            'nom_plume' => $_POST['nom_plume'],
            'ad_mail_joueur' => $_POST['ad_mail_joueur'],
            'mot_de_passe_joueur' => $_POST['mot_de_passe_joueur'],
            'sexe' => $_POST['sexe'],
            'ddn' => $_POST['ddn'],
        ];
        Inscription::getInstance()->registerUser($userData);
        HTTP::redirect('/connexion');
    }
}
