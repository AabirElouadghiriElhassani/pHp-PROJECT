<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/salles', name: 'app_salle_')]
class SalleController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $salles = [
            ['id' => 1, 'nom' => 'Salle 1', 'capacite' => 50, 'occupation' => 30],
            ['id' => 2, 'nom' => 'Salle 2', 'capacite' => 40, 'occupation' => 25],
            ['id' => 3, 'nom' => 'Salle 3', 'capacite' => 60, 'occupation' => 15]
        ];
        
        return $this->render('salle/index.html.twig', ['salles' => $salles]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(int $id): Response
    {
        $salle = ['id' => $id, 'nom' => 'Salle #' . $id, 'capacite' => 50];
        return $this->render('salle/show.html.twig', ['salle' => $salle]);
    }
}

