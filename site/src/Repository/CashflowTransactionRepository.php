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

    public function listByCompanyId(string $id, ?string $projectId = null): array
    {
        Assert::uuid($id);

        $qb = $this->repo->createQueryBuilder('t')
            ->andWhere('t.company = :company')
            ->setParameter('company', $id);

        if ($projectId !== null) {
            Assert::uuid($projectId);
            $qb->andWhere('t.project = :project')
                ->setParameter('project', $projectId);
        }

        return $qb->getQuery()->getResult();
    }

    public function countByCompanyId(string $id): int
    {
        Assert::uuid($id);
        return (int)$this->repo->createQueryBuilder('t')
            ->select('count(t.id)')
            ->andWhere('t.company = :company')
            ->setParameter('company', $id)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function paginateByCompanyId(string $id, int $page, int $perPage): array
    {
        Assert::uuid($id);
        Assert::greaterThanEq($page, 1);
        Assert::greaterThanEq($perPage, 1);

        $qb = $this->repo->createQueryBuilder('t')
            ->andWhere('t.company = :company')
            ->setParameter('company', $id)
            ->orderBy('t.date', 'DESC')
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        return $qb->getQuery()->getResult();
    }

}
