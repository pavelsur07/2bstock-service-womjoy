<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity]
#[ORM\Table(name: '`cash_flow_transactions`')]
class CashflowTransaction
{



    public const INCOME = 'income';
    public const EXPENSE = 'expense';

    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $id = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'float')]
    private float $amount;

    #[ORM\Column(type: 'string', length: 10)]
    private string $direction; // income, expense

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comment = null;

    #[ORM\ManyToOne(targetEntity: CashflowCategory::class, inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private CashflowCategory $category;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'cashflowTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    private Company $company;

    #[ORM\ManyToOne(targetEntity: CashAccount::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?CashAccount $account = null;

    public function __construct(string $id)
    {
        Assert::uuid($id);
        $this->id = $id;
    }


    public function getId(): ?string { return $this->id; }
    public function getDate(): \DateTimeInterface { return $this->date; }
    public function setDate(\DateTimeInterface $date): self { $this->date = $date; return $this; }
    public function getAmount(): float { return $this->amount; }

    public function setAccount(?CashAccount $account): void
    {
        $this->account = $account;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }
    public function getDirection(): string
    {

        return $this->direction;
    }
    public function setDirection(string $direction): self
    {
        Assert::oneOf($direction,
            [
                self::INCOME,
                self::EXPENSE,
            ]
        );
        $this->direction = $direction;
        return $this;
    }
    public function getComment(): ?string { return $this->comment; }
    public function setComment(?string $comment): self { $this->comment = $comment; return $this; }
    public function getCategory(): CashflowCategory { return $this->category; }
    public function setCategory(CashflowCategory $category): self { $this->category = $category; return $this; }
    public function getCompany(): Company { return $this->company; }
    public function setCompany(Company $company): self { $this->company = $company; return $this; }
}
