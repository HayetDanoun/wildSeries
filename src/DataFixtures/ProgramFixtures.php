<?php
namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const PROGRAMS = [
        [
            'title' => 'Belle plus la vie',
            'country'=> 'France',
            'synopsis' => 'un truc pour les vieilles',
            'year' => '2000',
        ],
        [
            'title' => 'teen wolf',
            'country'=> 'Etats-Unis',
            'synopsis' => 'des jeunes se transforment en loup-garou',
            'year' => '2001',
        ],
        [
            'title' => 'Walking dead',
            'country'=> 'Etats-Unis',
            'synopsis' => 'des zombies envahissent la terre',
            'year' => '2002',
        ],
        [
            'title' => 'desperate-housewives',
            'country'=> 'America',
            'synopsis' => 'un ville cachant bien des secret',
            'year' => '2003',
        ],
        [
            'title' => 'lol',
            'country'=> 'france',
            'synopsis' => 'c\'est chere drole des humoristes qui doivent essayer de se faire rire et eux ne doivent pas rigoler. Je vous le recommande :) ',
            'year' => '2004',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PROGRAMS as $newValueProgram) {
            $program = new Program();
            $program->setTitle($newValueProgram['title']);
            $program->setCountry($newValueProgram['country']);
            $program->setSynopsis($newValueProgram['synopsis']);
            $program->setYear($newValueProgram['year']);

            $category = $this->getReference('category_' . CategoryFixtures::CATEGORIES[array_rand(CategoryFixtures::CATEGORIES)]);
            $program->setCategory($category);

            $program->setCategory($category);
            $manager->persist($program);
            $this->addReference('program_'. str_replace(' ', '', $newValueProgram['title']),$program);
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
