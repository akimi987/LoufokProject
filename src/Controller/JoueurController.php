<?php

namespace App\Controller;

session_start();

use App\Model\CadavreModel;
use App\Model\Joueur;
use App\Model\Model;

class JoueurController extends Controller
{
    public function index($idGamer)
    {
        $leCadavreEnCours = CadavreModel::getInstance()->findCurrent();
        $leJoueur = Joueur::getInstance()->find($idGamer);

        $affichageFormulaire = true;
        $lesContributions = [];

        if ($leCadavreEnCours !== null) {
            $laContributionAleatoire = Joueur::getInstance()->findContributionRandom($leCadavreEnCours->getId(), $idGamer);
            $saContribution = Joueur::getInstance()->findHisContribution($leCadavreEnCours->getId(), $idGamer);

            if ($saContribution !== null) {
                $lesContributions = CadavreModel::getInstance()->findAllContributionsHideExcept($$leCadavreEnCours->getId(), $laContributionAleatoire->getId(), $saContribution->getId());
                $affichageFormulaire = false;
            } else {
                $lesContributions = CadavreModel::getInstance()->findAllContributionHideExcept($leCadavreEnCours->getId(), $laContributionAleatoire->getId());
            }
        }

        $vue = ($affichageFormulaire) ? 'Joueur' : 'NoGame';
        $variables = ['formulaire' => $affichageFormulaire, 'contributions' => $lesContributions];

        $this->display($vue . '.html.twig', $variables);
    }

    public function addContribution($idGamer, $idCadavre)
    {
        $leJoueur = Joueur::getInstance()->find($idGamer);
        $leCadavreEnCours = CadavreModel::getInstance()->findCurrent();

        if ($leCadavreEnCours !== null) {
            if ($leCadavreEnCours->getId() === $idCadavre) {
                $texte = $_POST['texte'];

                $model = new Model();
                $model->beginTransaction();

                try {
                    if (strlen($texte) < 50 || strlen($texte) > 280) {
                        throw new \Exception('La contribution doit contenir entre 50 et 280 caractères');
                    }
                    if ($leCadavreEnCours->getNbContributions() >= $leCadavreEnCours->getNbContributionsMax()) {
                        throw new \Exception('Le nombre maximal de contributions pour ce cadavre a été atteint');
                    }

                    $erreur = CadavreModel::getInstance()->addContribution($texte, $idGamer, $idCadavre);

                    if ($erreur) {
                        throw new \Exception('Erreur lors de l\'ajout de la contribution');
                    }

                    // Validez la transaction
                    $model->commit();

                    $this->display('Success.html.twig', ['message' => 'Contribution ajoutée avec succès']);
                } catch (\Exception $e) {
                    // En cas d'erreur,on annulez la transaction
                    $model->rollBack();
                    $this->display('ErrorAddContribution.html.twig', ['message' => $e->getMessage()]);
                }
            } else {
                $this->display('ErrorAddContribution.html.twig', ['message' => 'Le cadavre spécifié ne correspond pas au cadavre en cours']);
            }
        } else {
            $this->display('ErrorAddContribution.html.twig', ['message' => 'Aucun cadavre en cours']);
        }
    }
}
