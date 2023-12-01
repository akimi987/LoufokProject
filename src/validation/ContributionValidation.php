<?php
// ContributionValidator.php
namespace App\Validation;

class ContributionValidation
{
    public static function validateContribution($texte, $idCadavre, $idGamer, $cadavreModel)
    {
        $cadavreInfo = $cadavreModel->getCadavreInfos($idCadavre);
        $currentDate = date('Y-m-d H:i:s');
        if ($currentDate < $cadavreInfo['date_debut_cadavre'] || $currentDate > $cadavreInfo['date_fin_cadavre']) {
            return "La période de soumission pour ce cadavre est terminée.";
        }
        if ($cadavreModel->isPlayerContributed($idGamer, $idCadavre)) {
            return "Vous avez déjà contribué à ce cadavre. Vous ne pouvez pas soumettre plusieurs contributions.";
        }
        $contribLength = mb_strlen($texte, 'UTF-8');
        if ($contribLength < 50 || $contribLength > 280) {
            return "La contribution doit avoir entre 50 et 280 caractères.";
        }
        return null;
    }
}
