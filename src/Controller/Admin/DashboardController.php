<?php

namespace App\Controller\Admin;

use App\Entity\Ad;
use App\Entity\User;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Locale;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //

        // Récupérer tous les utilisateurs en faisant une requête DQL

        // Récupération de l'entity manager de Doctrine

        // Récupération de l'entity manager de Doctrine
        $entityManager = $this->entityManager;

        // Création de la requête DQL pour récupérer tous les utilisateurs
        $users = $entityManager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();

        // Création de la requête DQL pour récupérer toutes les annonces
        $ads = $entityManager->createQuery('SELECT COUNT(a) FROM App\Entity\Ad a')->getSingleScalarResult();

        // Création de la requête DQL pour récupérer toutes les réservations
        $bookings = $entityManager->createQuery('SELECT COUNT(b) FROM App\Entity\Booking b')->getSingleScalarResult();

        // Création de la requête DQL pour récupérer tous les commentaires
        $comments = $entityManager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();

        //dd($users, $ads, $bookings, $comments);

        // Listes des meilleurs annonces
        $bestAds = $entityManager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.profilPicture,a.coverImage,a.slug as slugAd , u.slug as slugUser
                FROM App\Entity\Comment c 
                JOIN c.ad a 
                JOIN a.author u 
                GROUP BY a
                ORDER BY note DESC')->setMaxResults(6)->getResult();

       // dd($bestAds);

       // Liste des mauvaises annonces
        $worstAds = $entityManager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.profilPicture,a.coverImage,a.slug as slugAd , u.slug as slugUser
                FROM App\Entity\Comment c 
                JOIN c.ad a 
                JOIN a.author u 
                GROUP BY a
                ORDER BY note ASC')->setMaxResults(6)->getResult();

        return $this->render('admin/dashboard.html.twig', [
            'stats' => compact('users', 'ads', 'bookings', 'comments'),
            'bestAds' => $bestAds,
            'worstAds' => $worstAds
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sym R Bnb') // Pour le titre de la page
            // ->setLocales(['fr', 'fr']) // Pour la langue de la page
            ->setTranslationDomain('admin') // Pour la traduction des mots
            //->setLocales(['fr', 'en', 'es'])
            ->setLocales([
                'en',
                Locale::new('fr', 'français', 'flag-icon flag-icon-fr'),
                Locale::new('es', 'español', 'flag-icon flag-icon-es'),
                Locale::new('de', 'deutsch', 'flag-icon flag-icon-de'),

            ]);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Annonces', 'fa-solid fa-bed', Ad::class);
        yield MenuItem::linkToCrud('Réservations', 'fa-solid fa-calendar-days', Booking::class);
        yield MenuItem::linkToCrud('Membres', 'fa-solid fa-user', User::class);
        yield MenuItem::linkToCrud('Avis', 'fa-brands fa-rocketchat', Comment::class);
        yield MenuItem::linkToCrud('Message', 'fa-solid fa-envelope', Contact::class);
    }
}
