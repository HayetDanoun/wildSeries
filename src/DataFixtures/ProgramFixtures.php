<?php
namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{

    const PROGRAMS = [
        [
            'title' => 'Plus Belle La Vie',
            'country'=> 'France',
            'synopsis' => 'Un truc pour les vieilles',
            'year' => '2000',
        ],
        [
            'title' => 'Teen Wolf',
            'country'=> 'Etats-Unis',
            'synopsis' => 'Des jeunes se transforment en loup-garou',
            'year' => '2001',
        ],
        [
            'title' => 'Walking dead',
            'country'=> 'Etats-Unis',
            'synopsis' => 'Des zombies envahissent la terre',
            'year' => '2002',
        ],
        [
            'title' => 'Desperate Housewives',
            'country'=> 'America',
            'synopsis' => 'Un ville cachant bien des secrets',
            'year' => '2003',
        ],
        [
            'title' => 'lol',
            'country'=> 'france',
            'synopsis' => 'C\'est chere drôle, en gros c\'est des humoristes qui doivent essayer de se faire rire et eux ne doivent pas rigoler. Je vous le recommande :) ',
            'year' => '2004',
        ],
    ];

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::PROGRAMS as $key => $newValueProgram) {
            $program = new Program();
            $program->setTitle($newValueProgram['title']);
            $program->setCountry($newValueProgram['country']);
            $program->setSynopsis($newValueProgram['synopsis']);
            $program->setYear($newValueProgram['year']);

            $slug = $this->slugger->slug($newValueProgram['title'])->lower();
            //$slug = $this->slugger->slug($program->getTitle());
            $program->setSlug($slug);

            $category = $this->getReference('category_' . CategoryFixtures::CATEGORIES[array_rand(CategoryFixtures::CATEGORIES)]);
            $program->setCategory($category);

            $program->setCategory($category);
            $manager->persist($program);
            $this->addReference('program_'. $key ,$program);
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
