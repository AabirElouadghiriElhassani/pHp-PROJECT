<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\Film;
use App\Entity\Salle;
use Doctrine\ORM\EntityManagerInterface;

class StatistiquesService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    /**
     * Nombre total de réservations.
     */
    public function getNombreReservations(): int
    {
        return (int) $this->em->getRepository(Reservation::class)
            ->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Chiffre d'affaires total (somme des montants payés).
     */
    public function getChiffreAffaires(): float
    {
        $qb = $this->em->createQueryBuilder()
            ->from('App\Entity\Paiement', 'p')
            ->select('COALESCE(SUM(p.montant), 0)');

        return (float) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Top N films les plus réservés.
     *
     * @return array [ ['titre' => 'Film 1', 'nbReservations' => 10], ... ]
     */
    public function getTopFilms(int $limit = 5): array
    {
        $qb = $this->em->createQueryBuilder()
            ->from(Reservation::class, 'r')
            ->join('r.creneau', 'c')
            ->join('c.film', 'f')
            ->select('f as film, COUNT(r.id) AS nbReservations')
            ->groupBy('f.id')
            ->orderBy('nbReservations', 'DESC')
            ->setMaxResults($limit);

        $rows = $qb->getQuery()->getResult();

        return array_map(function ($row) {
            /** @var Film $film */
            $film = $row['film'];

            return [
                'titre'           => $film->getTitre(),
                'nbReservations'  => (int) $row['nbReservations'],
            ];
        }, $rows);
    }

    /**
     * Taux d'occupation par salle = (places réservées / capacité totale sur tous les créneaux).
     *
     * @return array [ ['salle' => 'Salle 1', 'taux' => 0.75], ... ]
     */
    public function getTauxOccupationParSalle(): array
    {
        $salles = $this->em->getRepository(Salle::class)->findAll();
        $result = [];

        foreach ($salles as $salle) {
            $capacite = $salle->getCapacite();

            $qb = $this->em->createQueryBuilder()
                ->from(Reservation::class, 'r')
                ->join('r.creneau', 'c')
                ->select('COALESCE(SUM(r.nombrePlaces), 0) AS nbPlaces')
                ->where('c.salle = :salle')
                ->setParameter('salle', $salle);

            $nbPlaces = (int) $qb->getQuery()->getSingleScalarResult();

            $taux = $capacite > 0 ? $nbPlaces / $capacite : 0;

            $result[] = [
                'salle' => $salle->getNom(),
                'taux'  => $taux, // ex: 0.75 = 75%
            ];
        }

        return $result;
    }
}
