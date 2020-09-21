<?php

namespace App\Repository;

use App\Entity\TicketState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TicketState|null find($id, $lockMode = null, $lockVersion = null)
 * @method TicketState|null findOneBy(array $criteria, array $orderBy = null)
 * @method TicketState[]    findAll()
 * @method TicketState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketState::class);
    }

    /*public function getMaxId() 
    {
        return $this->createQueryBuilder()
            ->select('MAX(ts.id)')
            ->from('TicketState', 'ts')
            ->getQuery()
            ->getResult()
        ;
    }*/

    // /**
    //  * @return TicketState[] Returns an array of TicketState objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TicketState
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
