<?php

namespace App\Repository;

use App\Entity\CashAccount;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Webmozart\Assert\Assert;

class CashAccountRepository
{
    private EntityManagerInterface $em;
    /**
     * @var EntityRepository<CashAccount>
     */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(CashAccount::class);
    }

    public function listByCompanyId(string $id): array
    {
        Assert::uuid($id);
        return $this->repo->findBy(['company' => $id]);
    }
}
