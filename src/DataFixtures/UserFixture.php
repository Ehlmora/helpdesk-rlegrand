<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Role;
use App\Repository\RoleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;

class UserFixture extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder, RoleRepository $roleRepos){
        $this->encoder = $encoder;
        $this->roleRepos = $roleRepos;
    }

    public function load(ObjectManager $manager)
    {
        //* Compte d'administration
        $user = new User();
        $user->setFirstname('Olivier')
            ->setLastname('Da Pozzo')
            ->setAddress('36 Rue de la Gare')
            ->setCity('Rives en Seine')
            ->setPostalCode('76490')
            ->setPhone('0604495383')
            ->setMail('olivier.dapozzo@live.fr')
            ->setDescription('Compte d\'administration')
            ->setRole($this->roleRepos->find(1))
            ->setUsername('root')
            ->setPassword($this->encoder->encodePassword($user, 'toor'));
        $manager->persist($user);
        $manager->flush();
    }
}
