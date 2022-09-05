<?php

namespace App\Infrastructure\Repository;

use App\Domain\Repository\DiscountRepositoryInterface;
use App\Infrastructure\Entity\Discount;
use App\Infrastructure\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Discount>
 *
 * @method Discount|null find($id, $lockMode = null, $lockVersion = null)
 * @method Discount|null findOneBy(array $criteria, array $orderBy = null)
 * @method Discount[]    findAll()
 * @method Discount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscountRepository extends ServiceEntityRepository implements DiscountRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Discount::class);
    }

    public function add(Discount $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Discount $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getMaxDiscountsForProduct(Product $product): ?Discount
    {
        $r = $this->createQueryBuilder('d')
            ->andWhere('d.sku = :sku OR d.sku IS NULL')
            ->setParameter('sku', $product->getSku())
            ->andWhere('d.category = :category OR d.category IS NULL')
            ->setParameter('category', $product->getCategory())
            ->orderBy('d.value', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
        return $r[0] ?? null;
    }

    public function findByFilters(?string $sku, ?string $category)
    {
        $builder = $this->createQueryBuilder('d');
        if($sku){
            $builder = $builder->andWhere('d.sku = :sku')->setParameter('sku', $sku);
        }
        else if($category){
            $builder = $builder->andWhere('d.category = :category')->setParameter('category', $category);
        }
        return $builder->orderBy('d.value', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
}
