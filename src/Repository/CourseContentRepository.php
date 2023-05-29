<?php

namespace App\Repository;

use App\Entity\CourseContent;
use App\Entity\Level;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CourseContent>
 *
 * @method CourseContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourseContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourseContent[]    findAll()
 * @method CourseContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseContent::class);
    }

    public function save(CourseContent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CourseContent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countByLevel($level)
    {
        try {
            return $this->createQueryBuilder('cc')
                ->select('COUNT(cc)')
                ->innerJoin('cc.course', 'c', Join::WITH)
                ->andWhere('c.level=:level')
                ->setParameter('level', $level)
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
        return 0;
    }

//    /**
//     * @return CourseContent[] Returns an array of CourseContent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CourseContent
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
