<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

     public function __construct(UserPasswordEncoderInterface $passwordEncoder)
   {
        $this->passwordEncoder = $passwordEncoder;
   }

    public function load(ObjectManager $manager)
    {
        $contributor = new User();
        $contributor->setEmail('user@book.com')
            ->setPseudo('Bireux')
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordEncoder->encodePassword(
            $contributor,
            'pass'
        ));
        $this->addReference('Bireux', $contributor);
        $manager->persist($contributor);

        $jojo = new User();
        $jojo->setEmail('jojo@book.com')
            ->setPseudo('Jojo')
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordEncoder->encodePassword(
                $jojo,
                'pass'
            ));
        $this->addReference('Jojo', $jojo);
        $manager->persist($jojo);

        $manager->flush();
    }
}
