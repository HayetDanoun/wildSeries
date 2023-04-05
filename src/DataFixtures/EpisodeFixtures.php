<?php
namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use ReflectionClass;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        echo PHP_EOL . 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA';
        var_dump(SeasonFixtures::$SEASONS) ;
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }


}

//ancienne version, sans la description + year
//    public function load(ObjectManager $manager): void
//    {
//
//        $TAB_MAX_SEASON = [];
//        foreach (ProgramFixtures::PROGRAMS as $nb) {
//            $TAB_MAX_SEASON[] = rand(0, 10);
//
//        }
//        var_dump($TAB_MAX_SEASON);
//        foreach (ProgramFixtures::PROGRAMS as $key => $program) {
//            $programTitle = str_replace(' ', '', $program['title']);
//            $program = $this->getReference('program_' . $programTitle);
//
//            for ($j = 1; $j <= $TAB_MAX_SEASON[$key]; $j = $j + 1) {
//                echo $TAB_MAX_SEASON[$key];
//                $season = new Season();
//                $season->setNumber($j);
//                $season->setProgram($program);
//                $this->addReference('season' . $j . '_' . $programTitle, $season);
//                echo 'season' . $j . '_' . $programTitle .
//                    $manager->persist($season);
//            }
//            echo 'b';
//        }
//    }