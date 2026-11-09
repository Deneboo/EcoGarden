<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\Advice;
use App\DataFixtures\AdviceData;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(5);
         foreach (AdviceData::ADVICES as $data) {
            $advice = new Advice();
            $advice->setMonth($data['month']);
            $advice->setDescription($data['description']);

            $manager->persist($advice);
        }

        $manager->flush();
    }
}
