<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * Найти последний заказ по адресу электронной почты и идентификатору услуги.
     *
     * @param string $email
     * @param int $serviceId
     * @return Order|null
     */
    public function findLastByEmailAndServiceId(string $email, int $serviceId): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.email = :email')
            ->setParameter('email', $email)
            ->andWhere('o.Service = :serviceId')
            ->setParameter('serviceId', $serviceId)
            ->orderBy('o.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
