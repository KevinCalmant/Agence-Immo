<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Property>
 *
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    public function save(Property $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Property $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array<Property>
     */
    public function findAllNotSold(): array {
        return $this->findAllNotSoldQuery()
            ->orderBy('p.id', 'asc')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<Property>
     */
    public function findLatest(): array {
        return $this->findAllNotSoldQuery()
            ->orderBy('p.created_at', 'desc')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    private function findAllNotSoldQuery(): QueryBuilder {
        return $this->createQueryBuilder('p')->where('p.sold = false');
    }
}
