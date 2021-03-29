<?php

namespace App\Controller;

use App\Repository\MaisonRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(MaisonRepository $maisonRepository): Response
    {
        // $houses = $maisonRepository->findAll();
        $houses = $maisonRepository->findLastSix();

        return $this->render('home/index.html.twig', [
            'maisons' => $houses,
        ]);
    }
}
