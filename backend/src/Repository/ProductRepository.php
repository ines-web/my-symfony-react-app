<?php

namespace App\Repository;

use App\Entity\Product;
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
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Find products by name or id
     *
     * @param string $query The search query
     * @return Product[]
     */
    public function findByNameOrId(string $query): array
    {
        $queryBuilder = $this->createQueryBuilder('p');
        
        // If query is numeric, search by ID
        if (is_numeric($query)) {
            $queryBuilder->where('p.id = :query')
                ->setParameter('query', $query);
        } else {
            // Otherwise search by name (case insensitive)
            $queryBuilder->where('LOWER(p.name) LIKE LOWER(:query)')
                ->setParameter('query', '%' . $query . '%');
        }
        
        return $queryBuilder->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}