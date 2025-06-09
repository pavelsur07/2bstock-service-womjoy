<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity]
#[ORM\Table(name: '`projects`')]
class Project
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\ManyToOne(targetEntity: Company::class)]

    private ?Company $company = null;


    public function __construct(?string $id)
    {
        Assert::uuid($id);
        $this->id = $id;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): void
    {
        $this->company = $company;
    }

    public function getId(): ?string { return $this->id; }

    public function getName(): string { return $this->name; }

    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    public function isActive(): bool { return $this->isActive; }

    public function setIsActive(bool $isActive): self {
        $this->isActive = $isActive;
        return $this;
    }

    public function __toString(): string {
        return $this->name;
    }
}
