<?php

declare(strict_types=1);

namespace App\Controller;

session_start();

use App\Helper\HTTP;
use App\Model\CadavreModel;
use App\Model\ParticiperModel;
use App\Model\Contribution;

class JoueurController extends Controller
{
    public function index($id)
    {
        if (!isset($_SESSION['role'])) {
            HTTP::redirect('/');
        }

        $role = $_SESSION['role'];

        if ($role === 'administrateur') {
            HTTP::redirect("/administrateur/{$id}");
        }

        $cadavreModel = CadavreModel::getInstance();
        $idCadavre = $cadavreModel->getCadavreEnCours();
        $cadavreEnCours = $cadavreModel->getCadavreInfos($idCadavre);
        $idJoueur = $_SESSION['user_id'];

        if ($cadavreEnCours) {
            // S'il y a un cadavre en cours

            // Récupérer les contributions du cadavre en cours
            $contributionsCadavre = Contribution::getInstance()->getAllContributionsForCadavre($idCadavre);

            // Récupérer la contribution aléatoire pour le joueur
            $numContributionAleatoire = Contribution::getInstance()->attribuerContributionAleatoire($idJoueur, $idCadavre);
            $numContributionAleatoire = intval($numContributionAleatoire);
            $contributionAleatoire = Contribution::getInstance()->getRandomContribution($idCadavre, $numContributionAleatoire);

            // Vérifier si le joueur a déjà contribué
            $joueurAContribue = ParticiperModel::getInstance()->joueurAContribue($idJoueur, $idCadavre);

            // Si le joueur a déjà contribué, récupérer sa contribution
            $joueurContribution = $joueurAContribue ? ParticiperModel::getInstance()->getJoueurContribution($idJoueur, $idCadavre) : null;

            $saisieContribution = (!$joueurAContribue);

            $this->display('participation.html.twig', [
                'cadavreEnCours' => $cadavreEnCours,
                'contributionsCadavre' => $contributionsCadavre,
                'contributionAleatoire' => $contributionAleatoire,
                'joueurAContribue' => $joueurAContribue,
                'joueurContribution' => $joueurContribution,
                'saisieContribution' => $saisieContribution,
            ]);
        } else {
            // S'il n'y a pas de cadavre en cours
            $this->display('participation.html.twig', [
                'cadavreEnCours' => null,
                'message' => 'Aucun cadavre en cours pour le moment.',
            ]);
        }
    }

    public function soumettreContribution()
    {
        $idJoueur = $_SESSION['user_id'];
        $cadavreModel = CadavreModel::getInstance();
        $idCadavre = $cadavreModel->getCadavreEnCours();
        $texteContribution = trim($_POST['texteContribution']);
        $cadavreModel->addContribution($texteContribution, $idCadavre, $idJoueur);
    }
}