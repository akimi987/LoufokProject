<?php

namespace App\Controller;

use App\Model\Cadavre;
use App\Model\Joueur;

class JoueurController
{
    public function index($idGamer)
    {
        $leCadavreEnCours = Cadavre::getInstance()->findCurrent();
        $leJoueur = Joueur::getInstance()->find($idGamer);

        $affichageFormulaire = true;
        $lesContributions = [];

        if ($leCadavreEnCours !== null) {
            $laContributionAleatoire = Joueur::getInstance()->findContributionRandom($leCadavreEnCours["id_cadavre"], $idGamer);
            $saContribution = Joueur::findHisContribution($leCadavreEnCours["id_cadavre"], $idGamer);

            if ($saContribution !== null) {
                $lesContributions = Cadavre::getInstance()->findAllContributionsHideExcept($laContributionAleatoire["id_contribution"], $saContribution["id_contribution"]);
                $affichageFormulaire = false;
            } else {
                $lesContributions = Cadavre::getInstance()->findAllContributionsHideExcept($laContributionAleatoire["id_contribution"]);
            }
        }

        $vue = ($affichageFormulaire) ? 'Joueur' : 'NoGame';
        $variables = ['formulaire' => $affichageFormulaire, 'contributions' => $lesContributions];

        $this->display($vue . '.html.twig', $variables);
    }

    public function addContribution($idGamer, $idCadavre)
    {
    // Récupérer le joueur
    $leJoueur = Joueur::getInstance()->find($idGamer);

    // Récupérer le cadavre en cours
    $leCadavreEnCours = Cadavre::getInstance()->findCurrent();

    if ($leCadavreEnCours !== null) {
        if ($leCadavreEnCours["id_cadavre"] === $idCadavre) {
            $erreur = Cadavre::getInstance()->addContribution($texte, $idGamer, $idCadavre);
            if ($erreur) {
                $this->display('ErrorAddContribution.html.twig', ['message' => 'Erreur lors de l\'ajout de la contribution']);
            } 
        } else {
            $this->display('ErrorAddContribution.html.twig', ['message' => 'Le cadavre spécifié ne correspond pas au cadavre en cours']);
        }
    } else {
        $this->display('ErrorAddContribution.html.twig', ['message' => 'Aucun cadavre en cours']);
    }
}}