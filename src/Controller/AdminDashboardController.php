<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use App\Repository\SalleRepository;
use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN_GENERAL')]
class AdminDashboardController extends AbstractController
{
    #[Route('/stat', name: 'app_admin_stat', methods: ['GET'])]
    public function stat(
        ReservationRepository $reservationRepository,
        SalleRepository $salleRepository
    ): Response {
        $caGlobal      = $reservationRepository->getChiffreAffairesGlobal();
        $caParFilm     = $reservationRepository->getChiffreAffairesParFilm();
        $topFilms      = $reservationRepository->getTopFilms(5);
        $occupation    = $salleRepository->getOccupationParSalle();

        return $this->render('admin/dashboard.html.twig', [
            'ca_global'   => $caGlobal,
            'ca_par_film' => $caParFilm,
            'top_films'   => $topFilms,
            'occupation'  => $occupation,
        ]);
    }
}
