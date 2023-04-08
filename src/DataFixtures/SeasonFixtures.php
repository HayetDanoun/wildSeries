<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

//Tout d'abord nous ajoutons la classe Factory de FakerPhp
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    const NB_SEASON = 5;
    public function load(ObjectManager $manager): void
    {
        //Puis ici nous demandons à la Factory de nous fournir un Faker
        $faker = Factory::create();

        //$nameReferenciel = [] ;
        for($i = 0; $i < count(ProgramFixtures::PROGRAMS); $i++) { //ici count(ProgramFixtures::PROGRAMS) = 5 c'est si on aug ou diminue le nombre de fixture, cela change automatiquement
            for($j = 0; $j < self::NB_SEASON; $j++) {
                $season = new Season();
                //Ce Faker va nous permettre d'alimenter l'instance de Season que l'on souhaite ajouter en base
                $season->setNumber($j+1);
                $season->setYear($faker->year());
                $season->setDescription($faker->paragraphs(3, true));
                //$nameReferencielProgramme = 'program_' . $faker->numberBetween(0, 5);
                $nameReferencielProgramme = 'program_' . $i;
                $season->setProgram($this->getReference($nameReferencielProgramme));
                //echo $nameReferencielProgramme . '_season_' . $j . PHP_EOL;
                $addRef = $nameReferencielProgramme . '_season_' . $j;
                $this->addReference($addRef, $season);

                $manager->persist($season);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
