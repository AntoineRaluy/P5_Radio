<?php

namespace App\Repository;

use App\Entity\Track;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Track|null find($id, $lockMode = null, $lockVersion = null)
 * @method Track|null findOneBy(array $criteria, array $orderBy = null)
 * @method Track[]    findAll()
 * @method Track[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Track::class);
    }

    public function findAllPostedTracksOrderedByArtist()
    {   
            return $this->createQueryBuilder('t')
            ->Where('t.status = 1')
            ->orderBy('t.artist', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findTrack($artist, $title) {
        return $this->createQueryBuilder('Track')
            ->Where('Track.title LIKE :title')
            ->andWhere('Track.artist LIKE :artist')
            ->setParameter('title', '%'.$title.'%')
            ->setParameter('artist', '%'.$artist.'%')
            ->getQuery()
            ->execute();
    }

 
     // /**
    //  * @return Track[] Returns an array of Track objects
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
    public function findOneBySomeField($value): ?Track
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
