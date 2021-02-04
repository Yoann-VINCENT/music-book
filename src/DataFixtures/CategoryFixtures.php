<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        'Baby',
        'Children',
        'Adult',
        'Princess',
        'Dragons',
        'Animals',
        'Adventures',
        'Fantastic',
        'Fun',
    ];

    public function load(ObjectManager $manager)
    {
        foreach(self::CATEGORIES as $key => $categoryName) {
            $category = new Category();
            $category->setTitle($categoryName);
            $manager->persist($category);
            $this->addReference('category_' . $key, $category);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}