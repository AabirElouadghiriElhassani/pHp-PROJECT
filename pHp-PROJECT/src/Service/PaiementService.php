<?php

namespace App\Service;

use App\Entity\Paiement;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;

class PaiementService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function payerReservation(Reservation $reservation, float $montant, string $mode = 'simulation'): Paiement
    {
        $paiement = new Paiement();
        $paiement->setReservation($reservation);
        $paiement->setMontant($montant);
        $paiement->setMode($mode);
        $paiement->setStatut('reussi');
        $paiement->setDatePaiement(new \DateTimeImmutable());

        $reservation->setStatut('confirmee');

        $this->em->persist($paiement);
        $this->em->flush();

        return $paiement;
    }
}
