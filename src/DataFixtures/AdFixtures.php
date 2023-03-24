<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
//use Cocur\Slugify\Slugify;
use Faker\Generator;
use App\Entity\Image;
use League\ISO3166\ISO3166;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AdFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');
        // $lugify = new Slugify();
        $type = ['Loft', 'Maison', 'Appartement', 'Chambre', 'Studio', 'Maison de maître'];

        for ($i = 0; $i < 50; $i++) {

            $ad = new Ad();
            $ad->setTitle($faker->sentence())
                // ->setSlug($lugify->slugify($ad->getTitle()))
                ->setCoverImage($faker->imageUrl())
                ->setAdress($faker->address())
                ->setCity($faker->city())
                ->setZipCode($faker->postcode())
                ->setCountry($faker->country())
                ->setType($faker->randomElement($type))
                ->setIntroduction($faker->paragraph(2))
                ->setContent('<p>' . join('</p><p>', $faker->paragraphs(random_int(5, 10))) . '</p>')
                ->setPrice(mt_rand(40, 2000))
                ->setRooms(mt_rand(1, 15));
            //->setCreatedAt(new \DateTimeImmutable());


            for ($j = 0; $j < (mt_rand(2, 5)); $j++) {
                $image = new Image();
                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
        }




        $manager->flush();
    }
}
