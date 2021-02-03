<?php

namespace App\DataFixtures;

use App\Entity\Page;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PageFixtures extends Fixture implements DependentFixtureInterface
{
    const PAGES = [
        'L\'Air du vent' => [
            'book' => 'book_0',
            'image' => "pocahontas.jpg",
            'song' => 'pocahontas.mp3',
            'lyrics' => 'Pour toi je suis l\'ignorante sauvage
                        Tu me parles de ma différence
                        Je crois sans malveillance
                        Mais si dans ton langage
                        Tu emploies le mot sauvage
                        C\'est que tes yeux sont remplis de nuages
                        De nuages',
            'page_number' => 1,
        ],
        'Ce rêve bleu' => [
            'book' => 'book_0',
            'image' => "aladdin.jpg",
            'song' => 'aladdin.mp3',
            'lyrics' => 'Je vais t\'offrir un monde
                        Aux mille et une splendeurs
                        Dis-moi princesse
                        N\'as-tu jamais laissé parler ton cœur?',
            'page_number' => 2,
        ],
        'Bonjour' => [
            'book' => 'book_0',
            'image' => "belle.jpg",
            'song' => 'belle.mp3',
            'lyrics' => 'Tout est sage dans ce petit village
                        Où les jours se ressemblent tous
                        Un village où les gens parlent vraiment
                        De tout et de rien
                        Bonjour, bonjour
                        Bonjour, bonjour, bonjour',
            'page_number' => 3,
        ],
        'Lonely Day' => [
            'book' => 'book_1',
            'image' => "system.jpg",
            'song' => 'system.mp3',
            'lyrics' => 'Such a lonely day
                        And it\'s mine
                        The most loneliest day of my life
                        Such a lonely day
                        Should be banned
                        It\'s a day that I can\'t stand',
            'page_number' => 1,
        ],
        'Zelda' => [
            'book' => 'book_2',
            'image' => "zelda.jpg",
            'song' => 'zelda.mp3',
            'lyrics' => '...',
            'page_number' => 1,
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
        foreach (self::PAGES as $title => $data) {
            $page = new Page();
            $page->setTitle($title)
                ->setImage($data['image'])
                ->setSong($data['song'])
                ->setLyrics($data['lyrics'])
                ->setPageNumber($data['page_number'])
                ->setBook($this->getReference($data['book']))
                ->setSlug($this->slugify->generate($title));
            $manager->persist($page);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [BookFixtures::class];
    }

}