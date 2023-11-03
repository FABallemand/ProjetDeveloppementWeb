<?php

namespace App\DataFixtures;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$email, $role, $plainPassword]) {
            $user = new User();
            $password = $this->hasher->hashPassword($user, $plainPassword);
            $user->setEmail($email);
            $user->setPassword($password);

            $roles = array();
            $roles[] = $role;
            $user->setRoles($roles);

            $manager->persist($user);
        }
        $manager->flush();
    }

    /**
     * Generates initialization data for user: [email, role, plain_password]
     * @return \\Generator
     */
    private function getUserData()
    {
        yield ['admin@localhost','ROLE_ADMIN', 'admin'];
        yield ['fabien@localhost','ROLE_USER', 'fabien'];
        yield ['user_1@localhost','ROLE_USER', 'user_1'];
        yield ['user_2@localhost','ROLE_USER', 'user_2'];
        
    }
}
