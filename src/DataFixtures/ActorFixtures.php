<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;


class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const NB_ACTOR = 10;
    const NB_PROGRAM_BY_ACTOR = 3;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for($i = 0; $i < self::NB_ACTOR; $i++) {
            $actor = new Actor();
            $actor->setFirstname($faker->firstName);
            $actor->setLastname($faker->lastName());

            $actor->setBirthDate($faker->dateTime());
            for($j = 0; $j < self::NB_PROGRAM_BY_ACTOR; $j++) {
                $key = $faker->numberBetween(0, count(ProgramFixtures::PROGRAMS)-1);
                $program = $this->getReference('program_' . $key);
                $actor->addProgram($program);
            }
            $this->addReference('actor_' . $i, $actor);
            $manager->persist($actor);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
