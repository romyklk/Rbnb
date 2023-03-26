<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Booking;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('email'),
            TextField::new('firstName'),
            TextField::new('lastName'),
            SlugField::new('slug')->setTargetFieldName('lastName'),
            TextField::new('phone'),
            TextEditorField::new('introduction'),
            TextEditorField::new('presentation'),
            TextField::new('address'),
            TextField::new('postalCode'),
            TextField::new('city'),
            TextField::new('country'),
            ImageField::new('profilPicture', 'Profil')->hideOnForm(),
            AssociationField::new('ads', 'Nb. Annonces')->hideOnForm(),
            AssociationField::new('bookings', 'Nb. Réservations')->hideOnForm(),
            AssociationField::new('comments', 'Nb. Commentaires')->hideOnForm(),
            DateField::new('createdAt', 'Inscrit le')->hideOnForm(),
        ];
    }

    //Modifier un utilisateur

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance):void
    {
        $getFirstName = $entityInstance->getFirstName();
        $getLastName = $entityInstance->getLastName();
        $getSlug = $entityInstance->getSlug();
        $getPhone = $entityInstance->getPhone();
        $getIntroduction = $entityInstance->getIntroduction();
        $getPresentation = $entityInstance->getPresentation();
        $getAddress = $entityInstance->getAddress();
        $getPostalCode = $entityInstance->getPostalCode();
        $getCity = $entityInstance->getCity();
        $getCountry = $entityInstance->getCountry();
        $getProfilPicture = $entityInstance->getProfilPicture();

        $user = $entityManager->getRepository(User::class)->find($entityInstance->getId());

        
            $user->setFirstName($getFirstName);
            $user->setLastName($getLastName);
            $user->setSlug($getSlug);
            $user->setPhone($getPhone);
            $user->setIntroduction($getIntroduction);
            $user->setPresentation($getPresentation);
            $user->setAddress($getAddress);
            $user->setPostalCode($getPostalCode);
            $user->setCity($getCity);
            $user->setCountry($getCountry);
            $user->setProfilPicture($getProfilPicture);

            $entityManager->persist($user);
            $entityManager->flush();
        
            $this->addFlash('success', 'L\'utilisateur '. $getFirstName . ' ' . $getLastName . ' a bien été modifié !');

            //$this->redirectToRoute('admin');
    }


    // Supprimer un utilisateur

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance):void
    {
        $getFirstName = $entityInstance->getFirstName();
        $getLastName = $entityInstance->getLastName();

        $user = $entityManager->getRepository(User::class)->find($entityInstance->getId());

        // Vérifier si l'uttilisateur a des réservations en cours

        $booking = $entityManager->getRepository(Booking::class)->findBy(['booker' => $user]);

        if($booking)
        {
            $this->addFlash('danger', 'L\'utilisateur '. $getFirstName . ' ' . $getLastName . ' ne peut pas être supprimé car il a des réservations en cours !');
        }
        else
        {
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'L\'utilisateur '. $getFirstName . ' ' . $getLastName . ' a bien été supprimé !');
        }



        //$this->redirectToRoute('admin');
    }
    
}
