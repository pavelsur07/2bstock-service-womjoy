<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class ProjectFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $names = ['Основной бизнес', 'Проект Х', 'Сезонная акция', 'Тестовый запуск'];

        foreach ($names as $name) {
            $project = new Project(Uuid::uuid4()->toString());
            $project->setName($name);
            $manager->persist($project);
        }

        $manager->flush();
    }
}
