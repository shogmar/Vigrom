<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Currency;
use App\Entity\TransactionsTypes;
use App\Entity\Wallet;
use App\Entity\WalletStatus;
use App\Entity\ReasonForChange;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($a = 0; $a < 2; $a++) {
            $currency = new Currency();
            if($a==0) $currency->setCurrency("USD");
            if($a==1) $currency->setCurrency("RUB");
            $manager->persist($currency);
            $this->addReference('currency'.$a, $currency);
            $manager->flush();
        }

        for ($a = 0; $a < 2; $a++) {
            $transaction = new TransactionsTypes();
            if($a==0) $transaction->setTransaction("debit");
            if($a==1) $transaction->setTransaction("credit");
            $manager->persist($transaction);
            $this->addReference('transaction'.$a, $transaction);
            $manager->flush();
        }

        for ($a = 0; $a < 2; $a++) {
            $ReasonForChange = new ReasonForChange();
            if($a==0) $ReasonForChange->setReason("stock");
            if($a==1) $ReasonForChange->setReason("refund");
            $manager->persist($ReasonForChange);
            $this->addReference('ReasonForChange'.$a, $ReasonForChange);
            $manager->flush();
        }

        $decimals = 2;
        $divisor = pow(10, $decimals);

        for ($a = 0; $a < 10; $a++) {
            $wallet = new Wallet();
            $wallet->setSumma(mt_rand(0, 1000000 * $divisor) / $divisor);
            $wallet->setCurrency($this->getReference('currency'.mt_rand(0,1)));
            $manager->persist($wallet);
            $this->addReference('wallet'.$a, $wallet);
            $manager->flush();
        }

        /*for ($a = 0; $a < 10; $a++) {
            $walletStatus = new WalletTransactions();
            $walletStatus->setCurrency($this->getReference('currency'.mt_rand(0,1)));
            $walletStatus->setDateofoperation(new \DateTimeImmutable());
            $walletStatus->setWallet($this->getReference('wallet'.$a));
            $walletStatus->setTransaction($this->getReference('transaction'.mt_rand(0,1)));
            $walletStatus->setReasonforchange($this->getReference('ReasonForChange'.mt_rand(0,1)));
            $manager->persist($walletStatus);
            $manager->flush();
        }*/
    }
}
