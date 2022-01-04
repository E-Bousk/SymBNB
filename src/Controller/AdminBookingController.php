<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Service\Pagination;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    /**
     * Permet d'afficher toutes les réservations
     * 
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_bookings_index")
     * 
     * @param $page 
     * @param Pagination $pagination 
     * @return Response
     */
    public function index($page, Pagination $pagination): Response
    {
        $pagination->setEntityClass(Booking::class)
            ->setCurrentPage($page)
            ->setLimit(7)
            // ->setRouteName('admin_bookings_index') // La route est récupérée avec « RequestStack » dans le service « Pagination.php »
            // ->setTemplatePath('admin/partials/pagination.html.twig') // Le template est différent (exemple)

        ;

        return $this->render('admin/booking/index.html.twig', compact('pagination'));
    }

    /**
     * Permet d'afficher le formulaire d'édition de réservation
     * 
     * @Route("/admin/bookings/{id}/edit", name="admin_bookings_edit")
     * 
     * @param Booking $booking
     * @param Request $request
     * @param ObjectManager $manager 
     * @return Response 
     */
    public function edit(Booking $booking, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AdminBookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Équivalent : dans l'entité on 'PreUpdate' et calcul le montant lorsque celui-ci est 'empty'
                // $booking->setAmount($booking->getAd()->getPrice() * $booking->getDuration());
            $booking->setAmount(0);

            $manager->flush();

            $this->addFlash('success', "La réservation numéro <strong>{$booking->getId()}</strong> a bien été modifiée");
            return $this->redirectToRoute('admin_bookings_index');
        }

        return $this->render('admin/booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }
        
    /**
     * Permet de supprimer une réservation
     * 
     * @Route("/admin/bookings/{id}/delete", name="admin_bookings_delete")
     * 
     * @param Booking $booking
     * @param ObjectManager $manager
     * @return Response 
     */
    public function delete(Booking $booking, ObjectManager $manager): Response
    {
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash('success', "La réservation de <strong>{$booking->getBooker()->GetFullName()}</strong> a bien été supprimée !");

        return $this->redirectToRoute('admin_bookings_index');
    }
}
