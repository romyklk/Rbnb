<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{

    // Réservez votre bien
    #[Route('/ads/{slug}/book', name: 'app_booking_create')]
    #[IsGranted('ROLE_USER')]
    public function bookAds(Ad $ad, Request $request, BookingRepository $bookingRepository,EntityManagerInterface $manager){

        $booking = new Booking();

        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $user = $this->getUser();

            $booking->setBooker($user)
                    ->setReservationDate(new \DateTime())
                    ->setAd($ad);

            // Si les dates ne sont pas disponibles, message d'erreur
            if(!$booking->isBookableDates()){
                $this->addFlash(
                    'warning',
                    "Les dates que vous avez choisi ne peuvent être réservées : elles sont déjà prises."
                );
            }else{

            $manager->persist($booking);
            $manager->flush();

            return $this->redirectToRoute('app_booking_confirm', [
                'id' => $booking->getId(),
                'withAlert' => true
            ]);

        }

        }

        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);

    }

    // Confirmation de la réservation

    #[Route('/booking/{id}/confirm', name: 'app_booking_confirm')]
    #[IsGranted('ROLE_USER')]
    public function confirmBooking(Booking $booking, Request $request, EntityManagerInterface $manager){

       /*  if($booking->getBooker() !== $this->getUser()){
            return $this->redirectToRoute('app_home');
        }

        if($booking->getReservationDate() < new \DateTime()){
            return $this->redirectToRoute('app_home');
        }

        $booking->setIsConfirmed(true);
        $manager->persist($booking);
        $manager->flush(); */

        return $this->render('booking/show.html.twig', [
            'booking' => $booking
        ]);

    }


  /*   #[Route('/booking', name: 'app_booking_index', methods: ['GET'])]
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('booking/index.html.twig', [
            'bookings' => $bookingRepository->findAll(),
        ]);
    }  */

    #[Route('/booking/new', name: 'app_booking_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BookingRepository $bookingRepository): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookingRepository->save($booking, true);

            return $this->redirectToRoute('app_booking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('booking/new.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    #[Route('/booking/{id}', name: 'app_booking_show')]
    public function show(Booking $booking,Request $request,EntityManagerInterface $entityManagerInterface): Response
    {

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $comment->setAd($booking->getAd())
                    ->setCreatedAt(new \DateTime())
                    ->setAuthor($this->getUser());

            $entityManagerInterface->persist($comment);
            $entityManagerInterface->flush();


            $this->addFlash(
                'success',
                "Votre commentaire a bien été pris en compte !"
            );

            $this->redirectToRoute('app_booking_show', [
                'id' => $booking->getId()
            ]);
        }

        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
            
        ]);
    }

/*     #[Route('/booking/{id}/edit', name: 'app_booking_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Booking $booking, BookingRepository $bookingRepository): Response
    {
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookingRepository->save($booking, true);

            return $this->redirectToRoute('app_booking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    #[Route('/booking/{id}', name: 'app_booking_delete', methods: ['POST'])]
    public function delete(Request $request, Booking $booking, BookingRepository $bookingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$booking->getId(), $request->request->get('_token'))) {
            $bookingRepository->remove($booking, true);
        }

        return $this->redirectToRoute('app_booking_index', [], Response::HTTP_SEE_OTHER);
    } */

}
