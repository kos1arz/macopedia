<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('test@test.pl');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordEncoder->hashPassword($user, 'qwerty'));

        $manager->persist($user);
        $manager->flush();
    }
}
