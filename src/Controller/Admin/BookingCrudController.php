<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BookingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Booking::class;
    }



    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('booker', 'Réservé par'),
            AssociationField::new('ad', 'Annonce')->setFormTypeOption('by_reference', false)->hideOnForm(),
            DateField::new('startDate', 'Arrivée'),
            DateField::new('createdAt', 'Départ'),
            IntegerField::new('amount', 'Montant')->setNumberFormat('%d €'),
            TextareaField::new('comment', 'Message'),
            DateField::new('reservationDate', 'Date de réservation'),
            DateField::new('getCreatedAt', 'Disponible le'),
        ];
    }

    /*     public function updateEntity(EntityManagerInterface $entityManager, $entityInstance):void
    {

        $getBooker = $entityInstance->getBooker();
        $getAd = $entityInstance->getAd();
        $getAmount = $entityInstance->getAmount();
        $getComment = $entityInstance->getComment();
        $getCreatedAt = $entityInstance->getCreatedAt();
        $getStartDate = $entityInstance->getStartDate();
        $getReservationDate = $entityInstance->getReservationDate();

        $bookingtAuthor = $getBooker->getFirstName() . ' ' . $getBooker->getLastName();

        $getBooking = $entityManager->getRepository(Booking::class)->find($entityInstance->getId());

        if(isSubmited() && isValide()) {
            
            // Calculer la déférence entre les deux dates
            $getDuration = $getCreatedAt->diff($getStartDate)->days;
            // Calculer le montant total
            $getAmount = $getAd->getPrice() * $getDuration;
            // Mettre à jour le montant de la réservation
            $getBooking->setAmount($getAmount);
            // Mettre à jour le commentaire de la réservation
            $getBooking->setComment($getComment);
            // Mettre à jour la date de réservation
            $getBooking->setReservationDate($getReservationDate);
            // Mettre à jour la date de début de réservation
            $getBooking->setStartDate($getStartDate);
            // Mettre à jour la date de fin de réservation
            $getBooking->setCreatedAt($getCreatedAt);
            // Mettre à jour le nom de l'auteur de la réservation
            $getBooking->setBooker($bookingtAuthor);
            // Mettre à jour le statut de la réservation
            $getBooking->setIsBooked(true);
            // Mettre à jour la date de création de la réservation
            $getBooking->setCreatedAt(new \DateTime());
            // Mettre à jour la date de modification de la réservation
            $getBooking->setUpdatedAt(new \DateTime());
            // Mettre à jour la date de suppression de la réservation

            $this->addFlash('success', "La réservation de <strong>{$bookingtAuthor}</strong> a bien été modifiée !");
        }


    }


    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance):void
{
    $getBooker = $entityInstance->getBooker();
    $getAd = $entityInstance->getAd();
    $getAmount = $entityInstance->getAmount();
    $getComment = $entityInstance->getComment();
    $getCreatedAt = $entityInstance->getCreatedAt();
    $getStartDate = $entityInstance->getStartDate();
    $getReservationDate = $entityInstance->getReservationDate();

    $bookingAuthor = $getBooker->getFirstName() . ' ' . $getBooker->getLastName();

    $getBooking = $entityManager->getRepository(Booking::class)->find($entityInstance->getId());

        
        // Calculer la déférence entre les deux dates
        $getDuration = $getCreatedAt->diff($getStartDate)->days;
        // Calculer le montant total
        $getAmount = $getAd->getPrice() * $getDuration;
        // Mettre à jour le montant de la réservation
        $getBooking->setAmount($getAmount);
        // Mettre à jour le commentaire de la réservation
        $getBooking->setComment($getComment);
        // Mettre à jour la date de réservation
        $getBooking->setReservationDate($getReservationDate);
        // Mettre à jour la date de début de réservation
        $getBooking->setStartDate($getStartDate);
        // Mettre à jour la date de fin de réservation
        $getBooking->setCreatedAt($getCreatedAt);
        // Mettre à jour le nom de l'auteur de la réservation
        $getBooking->setBooker($getBooker);
        // Mettre à jour la date de création de la réservation
        // Mettre à jour la date de suppression de la réservation

        $entityManager->persist($getBooking);
        $entityManager->flush();

        $this->addFlash('success', "La réservation de <strong>{$bookingAuthor}</strong> a bien été modifiée !");
    
}

 */



    // Supprimer une réservation

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $getBooker = $entityInstance->getBooker();

        $bookingAuthor = $getBooker->getFirstName() . ' ' . $getBooker->getLastName();

        $getBooking = $entityManager->getRepository(Booking::class)->find($entityInstance->getId());

        $entityManager->remove($getBooking);
        $entityManager->flush();

        $this->addFlash('success', "La réservation de <strong>{$bookingAuthor}</strong> a bien été supprimée !");
    }

    // Modifier une réservation
/* 
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $getBooker = $entityInstance->getBooker();
        $getAd = $entityInstance->getAd();
        $getAmount = $entityInstance->getAmount();
        $getComment = $entityInstance->getComment();
        $getCreatedAt = $entityInstance->getCreatedAt();
        $getStartDate = $entityInstance->getStartDate();
        $getReservationDate = $entityInstance->getReservationDate();

        $bookingAuthor = $getBooker->getFirstName() . ' ' . $getBooker->getLastName();

        $getBooking = $entityManager->getRepository(Booking::class)->find($entityInstance->getId());


        // Calculer la déférence entre les deux dates
        $getDuration = $getCreatedAt->diff($getStartDate)->days;
        // Calculer le montant total
        $getAmount = $getAd->getPrice() * $getDuration;
        // Mettre à jour le montant de la réservation
        $getBooking->setAmount($getAmount);
        // Mettre à jour le commentaire de la réservation
        $getBooking->setComment($getComment);
        // Mettre à jour la date de réservation
        $getBooking->setReservationDate($getReservationDate);
        // Mettre à jour la date de début de réservation
        $getBooking->setStartDate($getStartDate);
        // Mettre à jour la date de fin de réservation
        $getBooking->setCreatedAt($getCreatedAt);
        // Mettre à jour le nom de l'auteur de la réservation
        $getBooking->setBooker($getBooker);
        // Mettre à jour la date de création de la réservation
        // Mettre à jour la date de suppression de la réservation

        $entityManager->persist($getBooking);
        $entityManager->flush();

        $this->addFlash('success', "La réservation de <strong>{$bookingAuthor}</strong> a bien été modifiée !");
    }


 */
}
