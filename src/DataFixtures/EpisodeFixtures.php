<?php
namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use ReflectionClass;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public static  $EPISODES = [];

    private function modifiedConstanteEpisodes()
    {
        $TAB_EPISODES = null ;
        foreach (SeasonFixtures::$SEASONS as $key => $season){
            $nb= rand(1,30);
            for($i=0 ; $i<$nb ; $i=$i+1) {
                $number = $i+1;
                $TAB_EPISODES[] = [
                    'number' =>$number,
                    'title' => 'Episode '. $number . " : Tintin et la patate mystere" ,
                    'synopsis' => 'Synopsis de l\'episde '. $number .  ', saison ' . $season['number'] . ' du programme ' . $season['programTitle'],
                    'seasonReference' => $season['nameReference'],
                    'nameReference' => 'episode' . $number . '_' . $season['nameReference'],
                ];
            }
        }

        $reflection = new ReflectionClass($this);
        $reflectionProperty = $reflection->getProperty('EPISODES');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(null, $TAB_EPISODES);
    }
    //ajoute des valeurs aleatoires
    public function load(ObjectManager $manager): void
    {
        $this->modifiedConstanteEpisodes();
        $TAB_EPISODES = $this::$EPISODES;

        foreach ($TAB_EPISODES as $key => $newEpisode) {
            $episode = new Episode();
            $episode->setNumber($newEpisode['number']);
            $episode->setTitle($newEpisode['title']);
            $episode->setSynopsis($newEpisode['synopsis']);
            $season = $this->getReference($newEpisode['seasonReference']);
            $episode->setSeason($season);
            $this->addReference($newEpisode['nameReference'], $episode);

            $manager->persist($episode);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}