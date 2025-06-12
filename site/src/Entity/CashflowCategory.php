<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity]
#[ORM\Table(name: '`cash_flow_categories`')]
class CashflowCategory
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?self $parent = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent', cascade: ['persist', 'remove'])]
    private Collection $children;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'cashflowCategories')]
    #[ORM\JoinColumn(nullable: false)]
    private Company $company;

    #[ORM\OneToMany(targetEntity: CashflowTransaction::class, mappedBy: 'category')]
    private Collection $transactions;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $sortOrder = 0;


    public function __construct(string $id, Company $company)
    {
        Assert::uuid($id);
        $this->id = $id;
        $this->company = $company;
        $this->children = new ArrayCollection();
        $this->transactions = new ArrayCollection();
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

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;
        return $this;
    }


    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }
        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->removeElement($child)) {
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }
        return $this;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): self
    {
        $this->company = $company;
        return $this;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function isLeaf(): bool
    {
        return $this->children->isEmpty();
    }

    public function getDepth(): int
    {
        $depth = 0;
        $parent = $this->getParent();
        while ($parent !== null) {
            $depth++;
            $parent = $parent->getParent();
        }
        return $depth;
    }

    public function getIndentedName(): string
    {
        return str_repeat('-- ', $this->getDepth()) . $this->name;
    }
    /*public function getTransactions(): Collection
    {
        return $this->transactions;
    }*/
}
