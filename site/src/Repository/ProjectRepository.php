<?php

namespace App\Repository;

use App\Entity\CashflowTransaction;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ProjectRepository
{
    private EntityManagerInterface $em;
    /**
     * @var EntityRepository<Project>
     */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Project::class);
    }

    public function findAll(): array
    {
        return $this->repo->findAll();
    }

    public function listByCompanyId(string $companyId): array
    {
        return $this->repo->findBy([
            'company' => $companyId,
        ], [
            'name' => 'ASC',
        ]);
    }
}
