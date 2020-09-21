<?php

namespace App\DataFixtures;

use App\Entity\Ticket;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class TicketFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /*$faker = Factory::create('fr_FR');
        $populator = new \Faker\ORM\Propel\Populator($generator);
        $populator->addEntity('Ticket', 50, [
            'DateEnd'
        ]);*/
        $manager->flush();
    }
}
