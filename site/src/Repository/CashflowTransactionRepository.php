<?php

namespace App\Repository;

use App\Entity\CashflowTransaction;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Webmozart\Assert\Assert;

class CashflowTransactionRepository
{
    private EntityManagerInterface $em;
    /**
     * @var EntityRepository<CashflowTransaction>
     */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(CashflowTransaction::class);
    }

    public function listByCompanyId(string $id): array
    {
        Assert::uuid($id);
        return $this->repo->findBy(['company' => $id]);
    }

}
