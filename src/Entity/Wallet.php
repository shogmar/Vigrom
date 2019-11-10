<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Ramsey\Uuid\Uuid;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WalletRepository")
 */
class Wallet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     */
    private $uuid;

    /**
     * @ORM\Column(type="float")
     */
    private $summa;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     */
    private $currency;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WalletTransactions", mappedBy="wallet", orphanRemoval=true)
     */
    private $wallet_transactions;

    function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->wallet_transactions = new ArrayCollection();
    }

    public function getUuid()
    {
        return $this->id;
    }

    public function getSumma(): float
    {
        return $this->summa;
    }

    public function setSumma(float $summa): self
    {
        $this->summa = $summa;

        return $this;
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

    /**
     * @return Collection|WalletTransactions[]
     */
    public function getWalletTransactions(): Collection
    {
        return $this->wallet_transactions;
    }

    public function addWalletTransaction(WalletTransactions $walletTransaction): self
    {
        if (!$this->wallet_transactions->contains($walletTransaction)) {
            $this->wallet_transactions[] = $walletTransaction;
            $walletTransaction->setWallet($this);
        }

        return $this;
    }

    public function removeWalletTransaction(WalletTransactions $walletTransaction): self
    {
        if ($this->wallet_transactions->contains($walletTransaction)) {
            $this->wallet_transactions->removeElement($walletTransaction);
            // set the owning side to null (unless already changed)
            if ($walletTransaction->getWallet() === $this) {
                $walletTransaction->setWallet(null);
            }
        }

        return $this;
    }
}
