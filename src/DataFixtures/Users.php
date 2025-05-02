<?php

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Yaml\Yaml;


class Users extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher,
                                private readonly string                      $projectDir)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $dataYamlLink = $this->projectDir . '/fixtures/data/users.yaml';
        $data = Yaml::parseFile($dataYamlLink)["users"];

        foreach ($data as $userData) {
            $user = new User();
            $user->setEmail($userData["email"]);
            $user->setRoles($userData["roles"]);
            $user->setPassword($this->passwordHasher->hashPassword($user, $userData["password"]));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
