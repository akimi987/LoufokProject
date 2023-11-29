<?php

declare(strict_types=1); // strict mode

namespace App\Controller;

session_start();

use App\Helper\HTTP;
use App\Model\CadavreModel;
use App\Validation\CadavreValidation;

class AdministrateurController extends Controller
{
    public function index($id)
    {
        if (isset($_SESSION['role'])) {
            $role = $_SESSION['role'];

            if ($role === 'joueur') {
                HTTP::redirect("/joueur/{$id}");
            } else {
                $this->display('administrateur/creationCadavre.html.twig');
            }
        } else {
            HTTP::redirect('/');
        }
    }

    public function demarrerCadavre()
    {
        $idAdministrateur = $_SESSION['user_id'];
        $cadavreModel = CadavreModel::getInstance();
        $validation = new CadavreValidation($cadavreModel);
        $errors = $validation->validate(
            [
                'titre_cadavre' => trim($_POST['titre']),
                'date_debut_cadavre' => trim($_POST['dateDebut']),
                'date_fin_cadavre' => trim($_POST['dateFin']),
                'nb_contributions' => trim($_POST['nbContributions']),
            ],
            trim($_POST['premiereContribution'])
        );
        if (empty($errors)) {
            $cadavreData = [
                'titre_cadavre' => trim($_POST['titre']),
                'date_debut_cadavre' => trim($_POST['dateDebut']),
                'date_fin_cadavre' => trim($_POST['dateFin']),
                'nb_contributions' => trim($_POST['nbContributions']),
                'id_administrateur' => $idAdministrateur,
            ];

            $premiereContribution = trim($_POST['premiereContribution']);

            $cadavreId = $cadavreModel->createCadavre($cadavreData, $premiereContribution, $idAdministrateur);

            if ($cadavreId !== null) {
                $cadavre = $cadavreModel->getCadavreInfos($cadavreId);
                $this->display('administrateur/creationCadavre.html.twig', [
                    'successMessage' => 'Le cadavre a été créé avec succès.'
                ]);
            } else {
                $this->displayError(['Une erreur est survenue lors de la création du cadavre.']);
            }
        } else {
            $this->displayError($errors);
        }
    }
    private function displayError(array $errors)
    {
        foreach ($errors as $error) {
            echo "<p>Error: $error</p>";
        }
    }
    /*     public function demarrerCadavre()
    {
        $id = $_SESSION['user_id'];
        $cadavreId = CadavreModel::getInstance()->create([
            'titre_cadavre' => trim($_POST['titre']),
            'date_debut_cadavre' => trim($_POST['dateDebut']),
            'date_fin_cadavre' => trim($_POST['dateFin']),
            'nb_contributions' => trim($_POST['nbContributions']),
            'id_administrateur' => $id,
        ]);

        $date = date('Y-m-d H:i:s');
        Contribution::getInstance()->create([
            'texte_contribution' => trim($_POST['premiereContribution']),
            'date_soumission' => $date,
            'ordre_soumission' => 1,
            'id_administrateur' => $id,
            'id_cadavre' => $cadavreId,
        ]);
              $cadavre = CadavreModel::getInstance()->getCadavreInfos($cadavreId);
        $this->display('administrateur/details.html.twig', [
            'cadavre' => $cadavre,
        ]); 
    } */
}
