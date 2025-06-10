<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE_PROJECT = 'project';

    public function load(ObjectManager $manager): void
    {
        $company = $this->getReference(CompanyFixtures::REFERENCE_COMPANY, Company::class);
        $names = ['Проект Х', 'Сезонная акция', 'Тестовый запуск'];

        $project = new Project(Uuid::uuid4()->toString());
        $project->setName('Основной бизнес');
        $project->setCompany($company);
        $this->setReference(self::REFERENCE_PROJECT, $project);
        $manager->persist($project);

        foreach ($names as $name) {
            $project = new Project(Uuid::uuid4()->toString());
            $project->setName($name);
            $project->setCompany($company);
            $manager->persist($project);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CompanyFixtures::class,
        ];
    }
}
