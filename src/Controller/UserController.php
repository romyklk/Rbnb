<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user/{slug}', name: 'app_user_show')]
    public function index($slug,EntityManagerInterface $entityManagerInterface): Response
    {
        $user = $entityManagerInterface->getRepository(User::class)->findOneBy(['slug' => $slug]);
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
