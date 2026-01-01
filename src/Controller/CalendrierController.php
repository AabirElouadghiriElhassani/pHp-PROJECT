<?php
// src/Controller/CalendrierController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/calendrier', name: 'app_calendrier_')]
class CalendrierController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response 
    {
        return $this->render('calendrier/index.html.twig');
    }

    #[Route('/load', name: 'load', methods: ['GET'])]
    public function load(): JsonResponse 
    {
        // Données de test pour votre cinéma (sans base de données)
        return $this->json([
            [
                'id' => 1,
                'title' => 'Avengers Endgame - Salle 1 (30/50 places)',
                'start' => '2025-12-31T18:00:00',
                'end' => '2025-12-31T20:00:00',
                'placesRestantes' => 30,
                'url' => '/reservation/1'
            ],
            [
                'id' => 2,
                'title' => 'Inception - Salle 2 (25/40 places)',
                'start' => '2025-12-31T20:30:00',
                'end' => '2025-12-31T23:00:00',
                'placesRestantes' => 25,
                'url' => '/reservation/2'
            ],
            [
                'id' => 3,
                'title' => 'Dune - Salle 1 (10/50 places)',
                'start' => '2026-01-01T19:00:00',
                'end' => '2026-01-01T22:00:00',
                'placesRestantes' => 10,
                'url' => '/reservation/3'
            ],
            [
                'id' => 4,
                'title' => 'Oppenheimer - Salle 3 (45/60 places)',
                'start' => '2026-01-02T17:00:00',
                'end' => '2026-01-02T20:00:00',
                'placesRestantes' => 45,
                'url' => '/reservation/4'
            ]
        ]);
    }
}
