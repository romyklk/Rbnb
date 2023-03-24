<?php

namespace App\Controller;

use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\PasswordUpdateType;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    // Pour afficher le profil d'un utilisateur
    #[Route('/account', name: 'app_account')]
    public function show(): Response
    {
        $user = $this->getUser();
        
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }

    // Pour editer le profil
    #[Route('/account/profile', name: 'app_account_profile')]
    public function profile(Request $request,EntityManagerInterface $entityManagerInterface): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // On recupere le fichier
            $profilPictureFile = $form->get('profilPicture')->getData();
            if($profilPictureFile){
                // On genere un nouveau nom de fichier
                $originalFilename = pathinfo($profilPictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$profilPictureFile->guessExtension();

                // On deplace le fichier dans le dossier uploads
                try {
                    $profilPictureFile->move(
                        $this->getParameter('profile_user_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // On met a jour le nom du fichier dans l'entite
                $user->setProfilPicture($newFilename);
            }

            $user->setUpdatedAt(new \DateTimeImmutable());
            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();
            $this->addFlash('success', 'Votre profil a bien été modifié');
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Pour editer le mot de passe
    #[Route('/account/password-update', name: 'app_account_password')]
    public function updatePassword(Request $request,UserPasswordHasherInterface $userPasswordHasherInterface, EntityManagerInterface $entityManager): Response
    {
        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();
        
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // 1. Vérifier que le oldPassword du formulaire soit le même que le password de l'utilisateur
            if(!password_verify($passwordUpdate->getOldPassword(), $this->getUser()->getPassword())){
                // Gérer l'erreur

                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel"));
                
            }else{
                // 2. Si c'est le même, encoder le newPassword
                $newPassword = $passwordUpdate->getNewPassword();

                $password = $userPasswordHasherInterface->hashPassword($user, $newPassword);

                $this->getUser()->setPassword($password);
                $entityManager->persist($user);
                $entityManager->flush();
                
                $this->addFlash('success', 'Votre mot de passe a bien été modifié');
                return $this->redirectToRoute('app_account');
            }
        
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
