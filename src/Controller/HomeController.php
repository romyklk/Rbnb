<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager, CommentRepository $commentRepository): Response
    {
        // Récupérer les 6 annonces les mieux notées
        $bestAds = $entityManager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.profilPicture,a.coverImage,a.slug as slugAd , u.slug as slugUser, a.introduction
                FROM App\Entity\Comment c 
                JOIN c.ad a 
                JOIN a.author u 
                GROUP BY a
                ORDER BY note DESC')->setMaxResults(6)->getResult();
            
        // récupérer les 6 dernières annonces

        $lastAds = $entityManager->createQuery(
            'SELECT a.title, a.id, u.firstName, u.lastName, u.profilPicture,a.coverImage,a.slug as slugAd , u.slug as slugUser, a.introduction, a.city ,a.Country,a.zipCode
                FROM App\Entity\Ad a 
                JOIN a.author u 
                ORDER BY a.createdAt DESC')->setMaxResults(6)->getResult();
            
            //dd($lastAds);

            // Les derniers appartements réservés

            $lastBookings = $entityManager->createQuery(
                'SELECT b.id, b.startDate, b.createdAt, a.title, a.id as adId, u.firstName, u.lastName, u.profilPicture,a.coverImage,a.slug as slugAd , u.slug as slugUser, a.introduction, a.city ,a.Country,a.zipCode
                    FROM App\Entity\Booking b 
                    JOIN b.ad a 
                    JOIN a.author u 
                    ORDER BY b.createdAt DESC')->setMaxResults(6)->getResult();

            //dd($lastBookings);


            // Récupérer les 10 derniers commentaires

            $lastComments = $commentRepository->findBy([], ['id' => 'DESC'], 10);

           // dd($lastComments);
            

        

        return $this->render('home/index.html.twig', [
            'bestAds' => $bestAds,
            'lastAds' => $lastAds,
            'lastBookings' => $lastBookings,
            'lastComments' => $lastComments
        ]);
    }
}
