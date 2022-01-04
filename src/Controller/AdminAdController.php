<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Service\Pagination;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * Permet d'afficher toutes les annonces
     * 
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     * 
     * @param $page
     * @param Pagination $pagination 
     * @return Response
     */
    public function index($page, Pagination $pagination): Response
    {
        // Remplacé par le service « Pagination » :
            // $limit = 7;
            // $start = ($page * $limit) - $limit;
            // $totalPage = count($repo->findAll());
            // $NbrPages = ceil($totalPage / $limit);

            // return $this->render('admin/ad/index.html.twig', [
            //     'ads' => $repo->findby([], [], $limit, $start),
            //     'NbrPages' => $NbrPages,
            //     'page'=> $page
            // ]);

        $pagination->setEntityClass(Ad::class)
            ->setCurrentPage($page)
            ->setLimit(7)
        ;

        return $this->render('admin/ad/index.html.twig', compact('pagination'));
    }

    /**
     * Permet d'afficher le formulaire d'édition d'une annonce
     * 
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     * 
     * @param Ad $ad
     * @param Request $request
     * @param ObjectManager $manager 
     * @return Response 
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager): Response
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('success', "L'annonce <strong>{$ad->getTitle()}</strong> a bien été modifiée");
            return $this->redirectToRoute('admin_ads_index');
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une annonce
     * 
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     * 
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return Response 
     */
    public function delete(Ad $ad, ObjectManager $manager): Response
    {
        if (count($ad->getBookings()) > 0) {
            $this->addFlash('warning', "Vous ne pouvez pas supprimer l'annonce <strong>{$ad->getTitle()}</strong> car elle possède dejà des réservations");
        } else {
            $manager->remove($ad);
            $manager->flush();
    
            $this->addFlash('success', "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !");
        }

        return $this->redirectToRoute('admin_ads_index');
    }
}
