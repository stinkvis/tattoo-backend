<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/artists', name: 'artists_landing', methods: ['GET'])]
    public function artists(): Response
    {
        return $this->render('home/artists.html.twig');
    }
    #[Route('/consumer', name: 'consumer_landing', methods: ['GET'])]
    public function consumer(): Response
    {
        return $this->render('home/consumer.html.twig');
    }
}
