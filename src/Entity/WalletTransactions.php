<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\WalletTransactionsRepository")
 */
class WalletTransactions
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     */
    private $currency;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $exchange_rate_upon_change;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $dateofoperation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TransactionsTypes")
     * @ORM\JoinColumn(name="transaction_id", referencedColumnName="id")
     */
    private $transactiontypes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ReasonForChange")
     * @ORM\JoinColumn(name="reasonforchange_id", referencedColumnName="id")
     */
    private $reasonforchange;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Wallet", inversedBy="wallet_transactions")
     * @ORM\JoinColumn(name="wallet_uuid", referencedColumnName="uuid", nullable=false)
     */
    private $wallet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getExchange_rate_upon_change(): ?float
    {
        return $this->exchange_rate_upon_change;
    }

    public function setExchange_rate_upon_change(float $exchange_rate_upon_change): self
    {
        $this->exchange_rate_upon_change = $exchange_rate_upon_change;

        return $this;
    }

    public function getDateofoperation(): ?\DateTimeImmutable
    {
        return $this->dateofoperation;
    }

    public function setDateofoperation(\DateTimeImmutable $dateofoperation): self
    {
        $this->dateofoperation = $dateofoperation;

        return $this;
    }

    public function setTransaction(TransactionsTypes $transactiontypes): self
    {
        $this->transactiontypes = $transactiontypes;
        return $this;
    }

    public function getTransaction(): ?TransactionsTypes
    {
        return $this->transactiontypes;
    }

    public function setReasonforchange(ReasonForChange $reasonforchange): self
    {
        $this->reasonforchange = $reasonforchange;
        return $this;
    }

    public function getReasonforchange(): ?ReasonForChange
    {
        return $this->reasonforchange;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): self
    {
        $this->wallet = $wallet;

        return $this;
    }
}
