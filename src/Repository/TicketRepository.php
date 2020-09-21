<?php

namespace App\Repository;

use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent:: __construct($registry, Ticket::class);
        $this->security = $security;
    }

    // /**
    //  * @return Ticket[] Returns an array of Ticket objects
    //  */
    
    public function findAllByUserId($user_id)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user = :user_id')
            ->setParameter('user_id', $user_id)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Ticket
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
