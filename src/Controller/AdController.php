<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/ads/{slug}", name="ads_show")
     * 
     * @return Response 
     */
    public function show(Ad $ad): Response
    {
        return $this->render('ad/show.html.twig', compact('ad'));
    }
}
