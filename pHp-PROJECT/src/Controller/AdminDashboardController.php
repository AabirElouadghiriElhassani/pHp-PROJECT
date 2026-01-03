<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use App\Repository\SalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN_GENERAL')]
class AdminDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_admin_dashboard', methods: ['GET'])]
    public function stat(
        Request $request,
        ReservationRepository $reservationRepository,
        SalleRepository $salleRepository
    ): Response {
        // Filtre période (optionnel)
        $periode = $request->query->get('periode', 'all'); // all | month | week

        // Statistiques globales de ventes
        $caGlobal = $reservationRepository->getChiffreAffairesGlobal($periode);
        $caParFilm = $reservationRepository->getChiffreAffairesParFilm($periode);

        // Films les plus réservés
        $topFilms = $reservationRepository->getTopFilms(5, $periode);

        // Taux d’occupation des salles
        $occupation = $salleRepository->getOccupationParSalle($periode);

        return $this->render('admin/dashboard.html.twig', [
            'periode'    => $periode,
            'ca_global'  => $caGlobal,
            'ca_par_film'=> $caParFilm,
            'top_films'  => $topFilms,
            'occupation' => $occupation,
        ]);
    }

    #[Route('/stat/csv', name: 'app_admin_stat_csv', methods: ['GET'])]
    public function exportCsv(ReservationRepository $reservationRepository): Response
    {
        $data = $reservationRepository->getChiffreAffairesParFilm('all');

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="statistiques_films.csv"');

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['Film', 'Nombre de réservations', 'Chiffre d\'affaires (MAD)'], ';');

        foreach ($data as $row) {
            $film = $row['film'];
            fputcsv($handle, [
                $film->getTitre(),
                $row['nbReservations'],
                $row['ca'],
            ], ';');
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        $response->setContent($content);

        return $response;
    }
}
