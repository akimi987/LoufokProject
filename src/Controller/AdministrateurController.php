<?php

declare(strict_types=1); // strict mode

namespace App\Controller;

session_start();

use App\Helper\HTTP;
use App\Model\CadavreModel;
//use Cadavre;


class AdministrateurController extends Controller
{

    public function index($id)
    {
        if (isset($_SESSION['role'])) {
            $role = $_SESSION['role'];

            if ($role === 'joueur') {
                HTTP::redirect("/joueur/{$id}");
            } else {
                $cadavreEnCours = CadavreModel::getInstance()->findCurrent();

                if ($cadavreEnCours) {
                    $variables = ['cadavreEnCours' => $cadavreEnCours];
                    $this->display('administrateur/details.html.twig', $variables);
                } else {
                    $this->display('administrateur/creationCadavre.html.twig');
                }
            }
        } else {
            HTTP::redirect('/');
        }
    }
    public function demarrerCadavre()
    {
        $titre = $_POST['titre'];
        $dateDebut = $_POST['dateDebut'];
        $dateFin = $_POST['dateFin'];
        $nbContributions = $_POST['nbContributions'];
        $premiereContributionTexte = $_POST['premiereContribution'];

        $id = $_SESSION['user_id'];
        $cadavreId = CadavreModel::getInstance()->createCadavre($titre, $dateDebut, $dateFin, $nbContributions);

        if ($cadavreId) {
            CadavreModel::getInstance()->addContribution($premiereContributionTexte, $cadavreId, $id);
            $this->display('administrateur/details.html.twig', ['cadavreId' => $cadavreId]);
        } else {
            $this->display('administrateur/error.html.twig', ['error' => 'Erreur lors de la création du cadavre. Veuillez réessayer.']);
        }
    }
}
