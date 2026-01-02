<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReservationController extends AbstractController
{
    #[Route('/reservations', name: 'reservation_index')]
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig');
    }

    #[Route('/reservations/{id}', name: 'reservation_show')]
    public function show(int $id): Response
    {
        return $this->render('reservation/show.html.twig', [
            'id' => $id
        ]);
    }
}
