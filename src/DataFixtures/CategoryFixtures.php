<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
//    //1er methode pour rajouter un element
//    public function load(ObjectManager $manager): void
//    {
//         $category = new Category();
//         $category->setName('Horreur');
//         $manager->persist($category);
//        $manager->flush();
//    }
//
//    //2eme methode te casse pas la tete
//    public function load(ObjectManager $manager): void
//    {
//        for ($i = 1; $i <= 50; $i++) {
//            $category = new Category();
//            $category->setName('Nom de catégorie ' . $i);
//            $manager->persist($category);
//        }
//        $manager->flush();
//    }

    //3eme methode plus realiste
    const CATEGORIES = ['Action','Aventure','Animation','Fantastique','Horreur',];
    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $values) {
            $category = new Category();
            $category->setName($values) ;
            $manager->persist($category);
            $this->addReference('category_'. $values,$category);
        }
        $manager->flush();
    }

}
