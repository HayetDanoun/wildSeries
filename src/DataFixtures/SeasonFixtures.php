<?php
namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use ReflectionClass;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public static  $SEASONS = [];

    private function modifiedConstante()
    {
        $TAB_MAX_SEASON = [];
        foreach (ProgramFixtures::PROGRAMS as $key => $program){
            $nb= rand(1,10);
            for($i=0 ; $i<$nb ; $i=$i+1) {
                $TAB_MAX_SEASON [$key][] = [
                    'number' => $i +1,
                    'year' => 2000 + $i,
                    'description' => 'Description de la saison ' . $i +1 . ' pour le programme ' . $program['title'],
                ];
            }
        }

        $reflection = new ReflectionClass($this);
        $reflectionProperty = $reflection->getProperty('SEASONS');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(null, $TAB_MAX_SEASON);
    }
    //ajoute des valeurs aleatoire
    public function load(ObjectManager $manager): void
    {
        $this->modifiedConstante();
        var_dump($this::$SEASONS);
        $TAB_MAX_SEASON = $this::$SEASONS;


        foreach (ProgramFixtures::PROGRAMS as $key => $program) {
            //pour un programme
                $newSeasons = $TAB_MAX_SEASON[$key];
                foreach ($newSeasons as $newSeason) {
                    $programTitle =  str_replace(' ','',$program['title']) ;

                    $season = new Season();
                    $season->setNumber($newSeason['number']);
                    $season->setYear($newSeason['year']);
                    $season->setDescription($newSeason['description']);
                    $programSeason = $this->getReference('program_' . $programTitle);
                    $season->setProgram($programSeason);
                    $this->addReference('season' . $newSeason['number'] . '_' . $programTitle , $season);

                    $manager->persist($season);
                }
        }
        $manager->flush();
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