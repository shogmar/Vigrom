<?php
namespace App\Service\Validations;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Constraints\Currency;
use Symfony\Component\Validator\Constraints\NotEqualTo;
use Symfony\Component\Validator\Constraints\Choice;

class TransactionValidator
{
    private $validator;

    function __construct()
    {
        $this->validator = Validation::createValidator();
    }

    public function validateUuid($uuid)
    {
        $violation_uuid = $this->validator->validate($uuid, [
            new Uuid(),
            new NotBlank(),
        ]);
        if (0 !== count($violation_uuid)) {
            foreach ($violation_uuid as $violation) {
                $err[] = 'uuid '.$violation->getMessage();
            }
            return ['error' => $err];
        }
        return TRUE;
    }

    public function validateType_transaction($type_transaction, $choice)
    {
        $violation_type_transaction = $this->validator->validate($type_transaction, [
            new Type(['type' => 'string']),
            new NotBlank(),
            new Choice($choice)
        ]);
        if (0 !== count($violation_type_transaction)) {
            foreach ($violation_type_transaction as $violation) {
                $err[] = 'type_transaction '.$violation->getMessage();
            }
            return ['error' => $err];
        }
        return TRUE;

    }

    public function validateSumma($summa)
    {
        $violation_summa = $this->validator->validate((float)$summa, [
            new Type(['type' => 'float']),
            new NotBlank(),
            new NotEqualTo(0.0)
        ]);
        if (0 !== count($violation_summa)) {
            foreach ($violation_summa as $violation) {
                $err[] = 'summa '.$violation->getMessage();
            }
            return ['error' => $err];
        }
        return TRUE;
    }

    public function validateCorrency($corrency, $choice)
    {
        $violation_corrency = $this->validator->validate($corrency, [
            new Currency(),
            new NotBlank(),
            new Choice($choice)
        ]);
        if (0 !== count($violation_corrency)) {
            foreach ($violation_corrency as $violation) {
                $err[] = 'corrency '.$violation->getMessage();
            }
            return ['error' => $err];
        }
        return TRUE;

    }

    public function validateReason_for_change($reason_for_change, $choice)
    {
        $violation_reason_for_change = $this->validator->validate($reason_for_change, [
            new Type(['type' => 'string']),
            new NotBlank(),
            new Choice($choice)
        ]);
        if (0 !== count($violation_reason_for_change)) {
            foreach ($violation_reason_for_change as $violation) {
                $err[] = 'reason_for_change '.$violation->getMessage();
            }
            return ['error' => $err];
        }
        return TRUE;
    }

    public function validateLast_days($days)
    {
        $violation_days = $this->validator->validate($days, [
            new Type(['type' => 'integer'])
        ]);
        if (0 !== count($violation_days)) {
            foreach ($violation_days as $violation) {
                $err[] = 'last days '.$violation->getMessage();
            }
            return ['error' => $err];
        }
        return TRUE;
    }
}