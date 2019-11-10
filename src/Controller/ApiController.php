<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Swap\Builder as SwapBuilder;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Wallet;
use App\Entity\WalletTransactions;
use App\Service\Validations\TransactionValidator;
use App\Entity\Currency;
use App\Entity\TransactionsTypes;
use App\Entity\ReasonForChange;

class ApiController extends AbstractFOSRestController
{
    private $swap;
    private $validator;

    function __construct(SwapBuilder $swap, TransactionValidator $validator)
    {
        $this->swap = $swap->build();
        $this->validator = $validator;
    }

    /**
     * @Rest\Post("/add_transaction", name="add_transaction")
     */
    public function addTransaction(Request $request)
    {
        //Диапозоны для валидации
        $currency = $this->getDoctrine()->getRepository(Currency::class)->findAll();
        foreach($currency as $val) $currency_arr[] = $val->getCurrency();
        $type_transaction = $this->getDoctrine()->getRepository(TransactionsTypes::class)->findAll();
        foreach($type_transaction as $val) $type_transaction_arr[] = $val->getTransaction();
        $reason_for_change = $this->getDoctrine()->getRepository(ReasonForChange::class)->findAll();
        foreach($reason_for_change as $val) $reason_for_change_arr[] = $val->getReason();

        //Валидация
        if(($res_uuid = $this->validator->validateUuid($request->request->get('uuid'))) !== TRUE) return $this->json($res_uuid);
        if(($res_transaction = $this->validator->validateType_transaction($request->request->get('type_transaction'), $type_transaction_arr)) !== TRUE) return $this->json($res_transaction);
        if(($res_summa = $this->validator->validateSumma($request->request->get('summa'))) !== TRUE) return $this->json($res_summa);
        if(($res_corrency = $this->validator->validateCorrency($request->request->get('corrency'), $currency_arr)) !== TRUE) return $this->json($res_corrency);
        if(($res_reason_for_change = $this->validator->validateReason_for_change($request->request->get('reason_for_change'), $reason_for_change_arr)) !== TRUE) return $this->json($res_reason_for_change);

        //Достаём кошелёк
        $wallet = $this->getDoctrine()
            ->getRepository(Wallet::class)
            ->findLastSummabyUuid($request->request->get('uuid'));

        //Проверяем, есть кошел или нет, смотрим Валюту, если не совпадает, меняем по курсу
        $rate = NULL;
        if (empty($wallet)) {
            return $this->json([
                'error' => 'error uuid'
            ]);
        } elseif($wallet[0]->getCurrency()->getCurrency() == $request->request->get('corrency')) {
            $ex = round($request->request->get('corrency')+(float)$request->request->get('summa'), 2, PHP_ROUND_HALF_DOWN);
        } else {
            $rate = $this->swap->latest($request->request->get('corrency')."/".$wallet[0]->getCurrency()->getCurrency());
            $ex = round($rate->getValue()*(float)$request->request->get('summa'), 2, PHP_ROUND_HALF_DOWN);
        }
        //Записываем
        $this->getDoctrine()
            ->getRepository(WalletTransactions::class)
            ->addTransaction($request->request->get('uuid'), 
                $request->request->get('type_transaction'), 
                (float)$request->request->get('summa'),
                $request->request->get('corrency'),
                $request->request->get('reason_for_change'),
                $ex
            );

        return $this->json([
            'message' => 'successfully'
        ]);
    }

    /**
     * @Rest\Get("/balance_status/{uuid}", name="balance_status")
     */
    public function returnBalanceWallet($uuid = NULL)
    {
        //Валидация
        if(($res_uuid = $this->validator->validateUuid($uuid)) !== TRUE) return $this->json($res_uuid);

        $wallet = $this->getDoctrine()
            ->getRepository(Wallet::class)
            ->findLastSummabyUuid($uuid);
        if (!empty($wallet)) {
            return $this->json([
                'balance' => $wallet[0]->getSumma(),
                'currensy' => $wallet[0]->getCurrency()->getCurrency()
            ]);
        } else {
            return $this->json([
                'error' => "error summa"
            ]);
        }        
    }

    /**
     * 
     * @Rest\Get("/transaction_days/{uuid}/{lastdays}/{reason_for_change}", name="change_status")
     */
    public function returnTransactionDays($uuid = NULL, $lastdays = 1, $reason_for_change = "refund")
    {
        $reason_for = $this->getDoctrine()->getRepository(ReasonForChange::class)->findAll();
        foreach($reason_for as $val) $reason_for_change_arr[] = $val->getReason();

        if(($res_uuid = $this->validator->validateUuid($uuid)) !== TRUE) return $this->json($res_uuid);
        if(($res_last_days = $this->validator->validateLast_days((int)$lastdays)) !== TRUE) return $this->json($res_last_days);
        if(($res_reason_for_change = $this->validator->validateReason_for_change($reason_for_change, $reason_for_change_arr)) !== TRUE) return $this->json($res_reason_for_change);

        $res = $this->getDoctrine()
            ->getRepository(WalletTransactions::class)
            ->findLastTransicationLastDays($uuid, $lastdays, $reason_for_change);
        
        if (empty($res)) {
            return $this->json([
                'summa za '.$lastdays." days" => 0
            ]);
        } else {
            return $this->json([
                'summa za '.$lastdays." days" => $res[0]["summa"]
            ]);
        }
    }


}
