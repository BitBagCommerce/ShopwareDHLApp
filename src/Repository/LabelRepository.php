<?php

declare(strict_types=1);

namespace BitBag\ShopwareAppSkeleton\Repository;

use BitBag\ShopwareAppSkeleton\Entity\Label;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LabelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Label::class);
    }

    public function add(Label $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Label $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findByOrderId(string $orderId, string $shopId): ?Label
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.orderId = :orderId')
            ->setParameter('orderId', $orderId)
            ->andWhere('l.shop = :shop')
            ->setParameter('shop', $shopId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
