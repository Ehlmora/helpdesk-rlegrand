<?php

namespace App\Repository;

use App\Entity\TicketLog;
use App\Entity\TicketLogLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method TicketLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method TicketLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method TicketLog[]    findAll()
 * @method TicketLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketLogRepository extends ServiceEntityRepository
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent:: __construct($registry, TicketLog::class);
        $this->security = $security;
    }

    // /**
    //  * @return TicketLog[] Returns an array of TicketLog objects
    //  */
    
    public function findAllByTicketId($ticket_id)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.ticket = :ticket_id')
            ->setParameter('ticket_id', $ticket_id)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function createTicketLogs($old_array, $new_array) {
        
    }

    /*
    public function findOneBySomeField($value): ?TicketLog
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
