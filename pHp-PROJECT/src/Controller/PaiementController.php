<?php

namespace App\Controller;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/paiement')]
#[IsGranted('ROLE_USER')]
class PaiementController extends AbstractController
{
    #[Route('/{id}', name: 'app_paiement', methods: ['GET', 'POST'])]
    public function payer(
        Reservation $reservation,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        // Vérifier que la réservation appartient bien à l'utilisateur connecté
        if ($reservation->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($request->isMethod('POST')) {
            $action = $request->request->get('action');

            if ($action === 'success') {
                // Validation automatique après paiement réussi
                $reservation->setStatut('PAYEE');
                $em->flush();

                $this->addFlash('success', 'Votre paiement a été validé. Bon film !');

                // Redirige vers la page profil / historique (adapte le nom de route)
                return $this->redirectToRoute('app_profil');
            }

            if ($action === 'fail') {
                // Annulation automatique en cas d’échec
                $reservation->setStatut('ANNULEE');
                $em->flush();

                $this->addFlash('error', 'Le paiement a échoué. Votre réservation a été annulée.');
                return $this->redirectToRoute('app_profil');
            }
        }

        return $this->render('paiement/index.html.twig', [
            'reservation' => $reservation,
        ]);
    }
}
