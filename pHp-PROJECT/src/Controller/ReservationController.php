<?php

namespace App\Controller;

use App\Entity\Creneau;
use App\Repository\CreneauRepository;
use App\Service\ReservationService;
use App\Service\PaiementService;
use App\Service\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ReservationController extends AbstractController
{
    #[Route('/reservation/{id}', name: 'app_reservation')]
    public function reserver(
        int $id,
        Request $request,
        CreneauRepository $creneauRepository,
        ReservationService $reservationService,
        PaiementService $paiementService,
        NotificationService $notificationService
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $creneau = $creneauRepository->find($id);
        if (!$creneau) {
            throw $this->createNotFoundException('Créneau introuvable');
        }

        // nbPlaces récupéré depuis un formulaire simple (input name="places")
        $nbPlaces = (int) $request->request->get('places', 1);

        try {
            $reservation = $reservationService->reserver($this->getUser(), $creneau, $nbPlaces);

            // paiement simulé
            $montant = $creneau->getFilm()->getPrix() * $nbPlaces;
            $paiement = $paiementService->payerReservation($reservation, $montant, 'simulation');

            // email
            $notificationService->envoyerConfirmationReservation($reservation);

            $this->addFlash('success', 'Réservation et paiement confirmés.');
        } catch (\RuntimeException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_creneaux');
    }
}
