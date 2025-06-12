<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity]
#[ORM\Table(name: '`companies`')]
class Company
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $id = null;

    #[ORM\Column(type: 'string', length: 60)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'companies')]
    private User $owner;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\OneToMany(targetEntity: CashflowCategory::class, mappedBy: 'company', cascade: ['ALL'],orphanRemoval: true)]
    private Collection $cashflowCategories;

    #[ORM\OneToMany(targetEntity: CashflowTransaction::class, mappedBy: 'company')]
    private Collection $cashflowTransactions;

    #[ORM\OneToMany(targetEntity: CashAccount::class, mappedBy: 'company')]
    private Collection $cashAccounts;

    //private Collection $products;
    //private Collection $supplies;
    //private Collection $pnlTransactions;
    //private Collection $pnlCategories;
    //private Collection $marketplaceIntegrations;
    //private Collection $transactions;

    public function __construct(
        string $id,
        string $name,
        User $owner,
        \DateTimeImmutable $createdAt
    )
    {
        $this->cashflowCategories = new ArrayCollection();
        $this->cashAccounts = new ArrayCollection();
        Assert::uuid($id);
        $this->id = $id;
        $this->name = $name;
        $this->owner = $owner;
        $this->createdAt = $createdAt;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getCashflowCategories(): Collection
    {
        return $this->cashflowCategories;
    }

    public function getCashflowTransactions(): Collection
    {
        return $this->cashflowTransactions;
    }

    public function getCashAccounts(): Collection
    {
        return $this->cashAccounts;
    }
}
