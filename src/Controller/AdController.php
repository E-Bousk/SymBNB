<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     * 
     * @param AdRepository $adRepository
     * @return Response 
     */
    public function index(AdRepository $adRepository): Response
    {
        $ads = $adRepository->findAll();

        return $this->render('ad/index.html.twig', compact('ads'));
    }

    

    /**
     * @Route("/ads/new", name="ads_create")
     * 
     * @return Response 
     */
    public function create(Request $request, ObjectManager $manager): Response
    {
        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            // Persiste les images ajoutées avec le formulaire ('collection')
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée"
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * 
     * @return Response 
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager): Response
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Persiste les images ajoutées avec le formulaire ('collection')
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "Les modifications de l'annonce <strong>{$ad->getTitle()}</strong> ont bien été effectuées"
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }


        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }

    /**
     * @Route("/ads/{slug}", name="ads_show")
     * 
     * @return Response 
     */
    public function show(Ad $ad): Response
    {
        return $this->render('ad/show.html.twig', compact('ad'));
    }




}
