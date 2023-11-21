<?php

declare(strict_types=1); // strict mode

namespace App\Controller;

session_start();

use App\Helper\HTTP;
use App\Model\CadavreModel;

class AdministrateurController extends Controller
{

    public function index($id)
    {
        if (isset($_SESSION['role'])) {
            $role = $_SESSION['role'];

            if ($role === 'joueur') {
                HTTP::redirect("/joueur/{$id}");
            } else {
                $id = $_SESSION['user_id'];
                $leCadavreEnCours = CadavreModel::getInstance()->findCurrent();
                if ($leCadavreEnCours) {
                    $cadavreID = CadavreModel::getInstance()->getCadavreEnCoursId();
                    $cadavreDetails = CadavreModel::getInstance()->getInfos($cadavreID);
                    $contributions = CadavreModel::getInstance()->findAllContributions($id);

                    $this->display('administrateur/details.html.twig', [
                        'cadavreDetails' => $cadavreDetails,
                        'contributions' => $contributions,
                    ]);
                } else {
                    $this->display('administrateur/creationCadavre.html.twig');
                }
            }
        } else {
            HTTP::redirect('/');
        }
    }

    public function demarrerNouveauCadavre()
    {

        $cadavreTitre = $_POST['titre'];
        $premiereContribution = $_POST['premiereContribution'];
        $dateDebut = $_POST['dateDebut'];
        $dateFin = $_POST['dateFin'];
        $maxContributions = $_POST['nbContributions'];

        $idAdmin = $_SESSION['user_id'];
        $cadavreID = CadavreModel::getInstance()->demarrerCadavre($cadavreTitre, $premiereContribution, $dateDebut, $dateFin, $maxContributions, $idAdmin);
        HTTP::redirect("/administrateur/details/{$cadavreID}");
        exit();
    }
}
