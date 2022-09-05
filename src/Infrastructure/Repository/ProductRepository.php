<?php

namespace App\Infrastructure\Repository;

use App\Domain\Repository\ProductRepositoryInterface;
use App\Infrastructure\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->flush();
        }
    }

    public function findFiveProductsByFilters(?int $priceLessThan, ?string $category)
    {
        $builder = $this->createQueryBuilder('p');
        if($priceLessThan){
            $builder = $builder->join('p.price', 'r')->where('r.original < :val')->setParameter('val', $priceLessThan);
        }
        if($category){
            $builder = $builder->andWhere('p.category = :val2')->setParameter('val2', $category);
        }
        return $builder->orderBy('p.id', 'ASC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }

    public function deleteAllProducts()
    {
        $q = $this->_em->createQuery('delete from App\Infrastructure\Entity\Product');
        $q->execute();
    }

    public function flush()
    {
        $this->getEntityManager()->flush();
    }
}
