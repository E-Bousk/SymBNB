<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    /**
     * Permet d'afficher le formulaire de reservation
     * 
     * @Route("/ads/{slug}/booking", name="booking")
     * @IsGranted("ROLE_USER")
     * 
     * @param Ad $ad
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response 
     */
    public function index(Ad $ad, Request $request, ObjectManager $manager): Response
    {
        $booking = new Booking;
        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $booking->setAd($ad)
                ->setBooker($this->getUser())
            ;

            // Vérifie si les dates demandées sont disponibles
            if (!$booking->isBookableDates()) {
                $this->addFlash('warning', 'Les dates choisies ne sont pas disponibles');
            } else {
                $manager->persist($booking);
                $manager->flush();
    
                return $this->redirectToRoute('booking_show', [
                    'id' => $booking->getId(),
                    'withSuccessAlert' => true
                ]);
            }
        }

        return $this->render('booking/booking.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher la page d'une réservation
     * 
     * @Route("/ads/booking/{id}", name="booking_show")
     * 
     * @param Booking $booking
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response 
     */
    public function show(Booking $booking, Request $request, ObjectManager $manager): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser())
                ->setAd($booking->getAd())
            ;

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success', 'Votre commentaire a bien été pris en compte');
        }

        return $this->render('booking/show.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }
}
