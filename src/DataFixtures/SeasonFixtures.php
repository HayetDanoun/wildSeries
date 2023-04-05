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

    private function modifiedConstanteSeasons()
    {
        $TAB_MAX_SEASON = null ;
        foreach (ProgramFixtures::PROGRAMS as $key => $program){
            $nb= rand(1,10);
            $programTitle =  str_replace(' ','',$program['title']) ;
            for($i=0 ; $i<$nb ; $i=$i+1) {
                $number = $i+1;
                $TAB_MAX_SEASON[] = [
                    'number' =>$number,
                    'year' => 2000 + $i,
                    'description' => 'Description de la saison ' . $number . ' pour le programme ' . $program['title'],
                    'programTitle' => $programTitle,
                    'programReference' => 'program_' . $programTitle,
                    'nameReference' => 'season' . $number . '_' . $programTitle,
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
        $this->modifiedConstanteSeasons();
        $TAB_MAX_SEASON = $this::$SEASONS;


        foreach ($TAB_MAX_SEASON as $key => $newSeason) {
            $season = new Season();
            $season->setNumber($newSeason['number']);
            $season->setYear($newSeason['year']);
            $season->setDescription($newSeason['description']);
            $programSeason = $this->getReference($newSeason['programReference']);
            $season->setProgram($programSeason);
            $this->addReference($newSeason['nameReference'], $season);

            $manager->persist($season);
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