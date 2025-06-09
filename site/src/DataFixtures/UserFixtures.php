<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class  UserFixtures extends Fixture
{
    public const REFERENCE_USER = 'user_user';

    public function __construct(private readonly UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $user = new User(
            uuid: Uuid::uuid4()->toString()
        );

        $user->setEmail('user@app.test');
        $hashed = $this->hasher->hashPassword(
            user: $user,
            plainPassword: 'password'
        );
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($hashed);
        $this->setReference(self::REFERENCE_USER, $user);
        $manager->persist($user);
        $manager->flush();
    }
}
