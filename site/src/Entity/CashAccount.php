<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity]
#[ORM\Table(name: '`cash_accounts`')]
class CashAccount
{

    public const CASH = 'cash';
    public const BANK = 'bank';
    public const VIRTUAL = 'virtual';

    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 50)]
    private string $type; // cash, bank, virtual

    #[ORM\Column(type: 'float')]
    private float $openingBalance;

    public function __construct(string $id)
    {
        Assert::uuid($id);
        $this->id = $id;
    }


    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;
        return $this;
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

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getOpeningBalance(): float
    {
        return $this->openingBalance;
    }

    public function setOpeningBalance(float $openingBalance): self
    {
        $this->openingBalance = $openingBalance;
        return $this;
    }
}
