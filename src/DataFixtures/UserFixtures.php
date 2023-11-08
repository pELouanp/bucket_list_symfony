<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@series.com');
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setPassword($this->hasher->hashPassword($admin, '1234'));

        $manager->persist($admin);

        $elouan = new User();
        $elouan->setUsername('elouan');
        $admin->setEmail('elouan@peron.com');
        $elouan->setPassword($this->hasher->hashPassword($elouan, '1234'));


        $manager->persist($elouan);
        $manager->flush();
    }
}
