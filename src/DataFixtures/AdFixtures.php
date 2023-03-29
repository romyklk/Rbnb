<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
//use Cocur\Slugify\Slugify;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Image;
use App\Entity\Booking;
use App\Entity\Comment;
use Cocur\Slugify\Slugify;
use League\ISO3166\ISO3166;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {


        $faker = Factory::create('fr_FR');

        $admin = new User();
        $admin->setFirstName('Admin')
            ->setLastName('Admin')
            ->setEmail('admin@admin.com')
            ->setAddress($faker->address())
            ->setCity($faker->city())
            ->setPostalCode($faker->postcode())
            ->setPhone($faker->phoneNumber())
            ->setCountry($faker->country())
            ->setCreatedAt(new \DateTimeImmutable())
            ->setPassword(password_hash('1', PASSWORD_DEFAULT))
            ->setIntroduction($faker->sentence())
            ->setPresentation('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
            ->setProfilPicture($faker->imageUrl())
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $users =[];
        $gender = ['female','male'];

        for ($i = 0; $i < 200; $i++) {
            $user = new User();
            $genre = $faker->randomElement($gender);
            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            if($genre == 'male') {
                $picture = $picture . 'men/' . $pictureId;
            } else {
                $picture = $picture . 'women/' . $pictureId;
            }
            
            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName($genre))
                ->setEmail($faker->email())
                ->setAddress($faker->address())
                ->setCity($faker->city())
                ->setPostalCode($faker->postcode())
                ->setPhone($faker->phoneNumber())
                ->setCountry($faker->country())
                ->setCreatedAt(new \DateTimeImmutable())
                ->setPassword(password_hash('password', PASSWORD_DEFAULT))
                ->setIntroduction($faker->sentence())
                ->setPresentation('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setProfilPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }

        
        // $lugify = new Slugify();
        $type = ['Loft', 'Maison', 'Appartement', 'Chambre', 'Studio', 'Maison de maître'];

        for ($i = 0; $i < 500; $i++) {

            $ad = new Ad();
            $ad->setTitle($faker->sentence())
                // ->setSlug($lugify->slugify($ad->getTitle()))
                ->setCoverImage('https://picsum.photos/'. mt_rand(100, 1000))
                ->setAdress($faker->address())
                ->setAuthor($faker->randomElement($users))
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
                $image->setUrl('https://picsum.photos/'. mt_rand(100, 1000))
                    ->setCaption($faker->sentence())
                    ->setAd($ad);
                $manager->persist($image);
            }

            // Gestion des réservations

            for ($j = 0; $j < mt_rand(0, 10); $j++) {
                $booking = new Booking();
                $createdAt = new \DateTime();
                $startDate = $faker->dateTimeBetween('-1 months');
                $duration = mt_rand(3, 10);
                $amount = $ad->getPrice() * $duration;
                $booker = $faker->randomElement($users);

                $comment = $faker->paragraph();

                $booking->setBooker($booker)
                    ->setAd($ad)
                    ->setStartDate($startDate)
                    ->setCreatedAt($createdAt)
                    ->setAmount($amount)
                    ->setReservationDate($faker->dateTimeBetween('-9 months'))
                    ->setComment($faker->paragraph(1))
                    ;

                $manager->persist($booking);

                // Gestion des commentaires

                if (mt_rand(0, 1)) {
                    $comment = new Comment();
                    
                    // Nombre de commentaires par réservation 
                    for($k = 0; $k < mt_rand(0, 50); $k++){ 
                        $comment->setAuthor($faker->randomElement($users))
                        ->setAd($ad)
                        ->setRating(mt_rand(1, 5))
                        ->setContent($faker->paragraph());

                        $manager->persist($comment);
                    }
                }


            }

            $manager->persist($ad);
        }

        $manager->flush();
    }
}
