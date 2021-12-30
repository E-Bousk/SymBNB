<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use App\Entity\User;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SymbnbFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');

        $users = [];
        $genres = ['women', 'men'];

        for ($i = 1; $i <=20; $i++) {
            $genre = $faker->randomElement($genres);

            $user = new User;
            $user->setFirstName($faker->firstName($genre ==='men' ? 'male' : 'female'))
                ->setLastName($faker->lastName())
                ->setEmail($faker->freeEmail())
                ->setIntroduction($faker->sentence())
                ->setDescription($faker->sentences(3, true))
                ->setPassword($this->encoder->encodePassword($user, 'password'))
                ->setPicture(sprintf('https://randomuser.me/api/portraits/%s/%s.jpg', $genre, $faker->randomNumber(2)))
            ;
            $manager->persist($user);

            $users[] = $user;
        }

        for ($i = 1; $i <= 30; $i++) {
            $title= $faker->sentence();
            $coverImage= $faker->imageUrl(1000,350);
            $introduction= $faker->paragraph(2);
            $content= '<p>' . join('<p></p>', $faker->paragraphs(5)) . '</p>';
            
            $ad= (new Ad())
                ->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($faker->randomElement($users))
            ;
            $manager->persist($ad);

            for ($j = 1; $j <= mt_rand(2, 5); $j++) {
                $image = new Image();
                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad)
                ;
                $manager->persist($image);
            }
        }
        $manager->flush();
    }
}
