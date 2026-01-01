<?php

namespace App\Service;

use App\Entity\Creneau;
use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ReservationService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function reserver(User $user, Creneau $creneau, int $nbPlaces): Reservation
    {
        // places déjà réservées sur ce créneau
        $repo = $this->em->getRepository(Reservation::class);
        $totalDejaReserve = $repo->createQueryBuilder('r')
            ->select('COALESCE(SUM(r.nombrePlaces), 0)')
            ->where('r.creneau = :creneau')
            ->setParameter('creneau', $creneau)
            ->getQuery()
            ->getSingleScalarResult();

        $capacite = $creneau->getSalle()->getCapacite();
        if ($totalDejaReserve + $nbPlaces > $capacite) {
            throw new \RuntimeException('Capacité de la salle dépassée');
        }

        $reservation = new Reservation();
        $reservation->setUser($user);
        $reservation->setCreneau($creneau);
        $reservation->setNombrePlaces($nbPlaces);
        $reservation->setStatut('en_attente');
        $reservation->setDateCreation(new \DateTimeImmutable());

        $this->em->persist($reservation);
        $this->em->flush();

        return $reservation;
    }
}
