<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    
    public function findAllByRole($role)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.role = :role')
            ->setParameter('role', $role)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function verifyUsername($username) {
        if(empty($this->createQueryBuilder('u')
            ->andWhere('u.username = :username')
            ->setParameter('username', $username)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult())
        ) { return false; } else { return true; }
    }

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
