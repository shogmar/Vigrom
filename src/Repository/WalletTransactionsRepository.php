<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\WalletTransactions;
use App\Entity\Wallet;
use App\Entity\Currency;
use App\Entity\TransactionsTypes;
use App\Entity\ReasonForChange;
use Doctrine\DBAL\Types\Type;

/**
 * @method WalletTransactions|null find($id, $lockMode = null, $lockVersion = null)
 * @method WalletTransactions|null findOneBy(array $criteria, array $orderBy = null)
 * @method WalletTransactions[]    findAll()
 * @method WalletTransactions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WalletTransactionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WalletTransactions::class);
    }

    function addTransaction($uuid, $type_transaction, $summa, $currency, $reason_for_change, $ex)
    {
        $em = $this->getEntityManager();
        $en_wallet = $em->getRepository(Wallet::class)->findBy(['uuid'=>$uuid]);
        $en_currency = $em->getRepository(Currency::class)->findBy(['currency'=>$currency]);
        $en_type_transaction = $em->getRepository(TransactionsTypes::class)->findBy(['transaction'=>$type_transaction]);
        $en_reason_for_change = $em->getRepository(ReasonForChange::class)->findBy(['reason'=>$reason_for_change]);
        $wallet_transication = new WalletTransactions();
        $wallet_transication->setCurrency($en_currency[0])
            ->setDateofoperation(new \DateTimeImmutable())
            ->setExchange_rate_upon_change($summa)
            ->setTransaction($en_type_transaction[0])
            ->setReasonforchange($en_reason_for_change[0])
            ->setWallet($en_wallet[0]);
        $em->persist($wallet_transication);
        $em->flush();
        
        $wallet_summa = $en_wallet[0]->getSumma() + $ex;
        $en_wallet[0]->setSumma($wallet_summa);
        $em->persist($en_wallet[0]);
        $em->flush();
    }

    function findLastTransicationLastDays($uuid, $lastdays, $reason_for_change)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT sum(u.exchange_rate_upon_change) as summa FROM App\Entity\WalletTransactions u 
            LEFT JOIN App\Entity\Wallet a WITH a.uuid = :uuid
            LEFT JOIN App\Entity\ReasonForChange b WITH b.reason = :reason_for_change
            WHERE (u.dateofoperation BETWEEN :from AND :to)"
        );
        $date = new \DateTime();
        $to = new \DateTime($date->format('Y-m-d')."23:59:59");
        $from = new \DateTime($date->modify("-".$lastdays." day")->format('Y-m-d'));
        $query->setParameters(['uuid' => $uuid, 'reason_for_change' => $reason_for_change]);
        $query->setParameter('from', $from, Type::DATETIME);
        $query->setParameter('to', $to, Type::DATETIME);
        $res = $query->getResult();
        return $res;
    }
}
