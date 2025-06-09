<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class CompanyFixtures extends Fixture implements DependentFixtureInterface
{

    public const REFERENCE_COMPANY = 'company_company';

    public function load(ObjectManager $manager): void
    {
        $user = $this->getReference(UserFixtures::REFERENCE_USER, User::class);

        $company  = new Company(
            id: Uuid::uuid4()->toString(),
            name: 'Company LLC',
            owner: $user,
            createdAt: new \DateTimeImmutable()
        );
        $this->setReference(self::REFERENCE_COMPANY, $company);

        $manager->persist($company);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
