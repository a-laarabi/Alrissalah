<?php

namespace App\Repository;

use App\Entity\UserProgresses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserProgresses>
 *
 * @method UserProgresses|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProgresses|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProgresses[]    findAll()
 * @method UserProgresses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserProgressesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserProgresses::class);
    }

    public function save(UserProgresses $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserProgresses $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countByUser($user)
    {
        try {
            return $this->createQueryBuilder('up')
                ->select('COUNT(DISTINCT up)')
                ->andWhere('up.user = :user')
                ->setParameter('user', $user)
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
        return 0;
    }

//    /**
//     * @return UserProgresses[] Returns an array of UserProgresses objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserProgresses
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
