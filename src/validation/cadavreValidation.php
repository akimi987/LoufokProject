<?php

namespace App\Validation;

use App\Model\CadavreModel;

class CadavreValidation
{
    private $cadavreModel;
    public function __construct(CadavreModel $cadavreModel)
    {
        $this->cadavreModel = $cadavreModel;
    }

    public function validate(array $cadavreData, string $premiereContribution): array
    {
        $errors = [];

        if (!$this->validateStartDate($cadavreData['date_debut_cadavre'])) {
            $errors[] = 'La date de début doit être supérieure ou égale à aujourd\'hui.';
        }

        if (!$this->validateTitleUniqueness($cadavreData['titre_cadavre'])) {
            $errors[] = 'Le titre du cadavre doit être unique.';
        }

        if (!$this->validateContributionLength($premiereContribution)) {
            $errors[] = 'La longueur de la contribution doit être entre 50 et 280 caractères.';
        }

        if (!$this->validateDatesOrder($cadavreData['date_debut_cadavre'], $cadavreData['date_fin_cadavre'])) {
            $errors[] = 'La date de fin doit être supérieure à la date de début.';
        }

        if (!$this->validateNoOverlap($cadavreData['date_debut_cadavre'], $cadavreData['date_fin_cadavre'])) {
            $errors[] = 'Les périodes des cadavres ne doivent pas se chevaucher.';
        }
        return $errors;
    }



    public function validateTitleUniqueness(string $title): bool
    {
        return !$this->cadavreModel->isTitleExists($title);
    }

    public function validateContributionLength(string $contribution): bool
    {
        $contributionLength = strlen($contribution);
        return ($contributionLength >= 50 && $contributionLength <= 280);
    }

    public function validateDatesOrder(string $startDate, string $endDate): bool
    {
        $startDateTimestamp = strtotime($startDate);
        $endDateTimestamp = strtotime($endDate);

        return ($endDateTimestamp > $startDateTimestamp);
    }

    public function validateNoOverlap(string $startDate, string $endDate): bool
    {
        return !$this->cadavreModel->hasOverlap($startDate, $endDate);
    }
    public function validateStartDate(string $startDate): bool
    {
        $aujourdhui = date('Y-m-d');
        $startDateWithoutTime = date('Y-m-d', strtotime($startDate));

        return ($startDateWithoutTime >= $aujourdhui);
    }
}