<?php

namespace App\Repository;

use App\Entity\Salle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Salle>
 */
class SalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salle::class);
    }

    public function getOccupationParSalle(): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.reservations', 'r')
            ->addSelect('COALESCE(SUM(r.placesReservees), 0) AS placesOccupees')
            ->addSelect('s.capacite AS capacite')
            ->groupBy('s.id')
            ->getQuery()
            ->getResult();
    }
}
