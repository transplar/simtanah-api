<?php

namespace App\Repository;

use App\Entity\DocumentFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DocumentFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentFile[]    findAll()
 * @method DocumentFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentFileRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DocumentFile::class);
    }

//    /**
//     * @return DocumentFile[] Returns an array of DocumentFile objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DocumentFile
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
