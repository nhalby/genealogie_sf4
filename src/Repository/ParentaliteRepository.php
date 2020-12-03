<?php

/*
 * Ceci sera ajouté dans tous vos fichiers PHP en entête.
 *
 * (c) Zozor <zozor@openclassrooms.com>
 *
 * A adapter et ré-utiliser selon vos besoins!
 */

namespace App\Repository;

use App\Entity\Parentalite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Parentalite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Parentalite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Parentalite[]    findAll()
 * @method Parentalite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParentaliteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parentalite::class);
    }

    /*static public function findEnfantParent(?int $parentalite)
    {
        return $this->createQueryBuilder('pa')
        ->select('pa.idPersonne')
        ->where('pa.idParent=:parentalite')
        ->setParameter('parentalite', $parentalite)
        ->getQuery()
        ->getResult();
    }*/

    // /**
    //  * @return Parentalite[] Returns an array of Parentalite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Parentalite
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
