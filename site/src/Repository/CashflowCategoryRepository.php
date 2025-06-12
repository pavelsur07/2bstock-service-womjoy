<?php

namespace App\Repository;

use App\Entity\CashflowCategory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;
use Webmozart\Assert\Assert;

class CashflowCategoryRepository
{
    private EntityManagerInterface $em;
    /**
     * @var EntityRepository<CashflowCategory>
     */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(CashflowCategory::class);
    }

    public function get(string $id): CashflowCategory
    {
        Assert::uuid($id);
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new DomainException(sprintf('CashflowCategory %s not found', $id));
        }

        return $object;
    }

    public function listByCompanyId(string $id): array
    {
        Assert::uuid($id);
        return $this->repo->findBy(['company' => $id]);
    }

    public function listLeavesByCompanyId(string $id): array
    {
        Assert::uuid($id);
        return $this->repo->createQueryBuilder('c')
            ->leftJoin('c.children', 'child')
            ->andWhere('c.company = :company')
            ->andWhere('child.id IS NULL')
            ->setParameter('company', $id)
            ->orderBy('c.sortOrder', 'ASC')
            ->addOrderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findBy(array $criteria, array|null $orderBy = null): array
    {
        return $this->repo->findBy($criteria, $orderBy);
    }
}
