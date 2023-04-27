<?php
namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use ReflectionClass;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    const NB_EPISODE = 10;

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for($i=0 ; $i< count(ProgramFixtures::PROGRAMS) ; $i++) {
            for($j=0 ; $j<SeasonFixtures::NB_SEASON ; $j++) {
                for ($k = 0; $k < self::NB_EPISODE; $k++) {
                    $episode = new Episode();
                    $episode->setNumber($k+1);
                    $episode->setTitle($faker->sentence(3));
                    $episode->setSynopsis($faker->paragraph(3, true));
                    $episode->setDuration($faker->numberBetween(40,60));
                    $slug = $this->slugger->slug($episode->getTitle())->lower();
                    $episode->setSlug($slug);

                    $nameSeason = 'program_' . $i. '_season_' . $j;
                    $season = $this->getReference($nameSeason);
                    $episode->setSeason($season);

                    $this->addReference($nameSeason . '_episode_' . $k, $episode);

                    $manager->persist($episode);
                }
            }
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