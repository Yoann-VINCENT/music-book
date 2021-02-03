<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Repository\CategoryRepository;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    const BOOKS = [
        'Super Princess' => [
            'cover' => "princesse.jpg"
        ],
        'System of a down' => [
            "cover" => "system.png",
        ],
        'Zelda' => [
            'cover' => "zelda.jpg",
        ],
    ];

    /**
     * @var Slugify
     */
    private $slugify;

    /**
     * ProgramFixtures constructor.
     * @param Slugify $slugify
     */
    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $i = 0;
        foreach (self::BOOKS as $title => $data) {
            $book = new Book();
            $book->setTitle($title)
                ->setCover($data['cover'])
                ->addCategory($this->getReference('category_3'))
                ->setSlug($this->slugify->generate($title))
                ->setAuthor($this->getReference('Bireux'))
                ->setCreatedAt(new \DateTime('now'));
            $manager->persist($book);
            $this->addReference('book_'.$i, $book);
            $i++;
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }
}