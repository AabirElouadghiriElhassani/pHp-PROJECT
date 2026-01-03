<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function getChiffreAffairesGlobal(): float
    {
        return (float) $this->createQueryBuilder('r')
            ->select('COALESCE(SUM(r.montant), 0)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getChiffreAffairesParFilm(): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.film', 'f')
            ->select('f AS film')
            ->addSelect('COALESCE(SUM(r.montant), 0) AS ca')
            ->groupBy('f.id')
            ->orderBy('ca', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getTopFilms(int $limit = 5): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.film', 'f')
            ->select('f AS film')
            ->addSelect('COUNT(r.id) AS nbReservations')
            ->groupBy('f.id')
            ->orderBy('nbReservations', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
