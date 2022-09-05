<?php
declare(strict_types=1);

namespace App\Application\Query;
use App\Application\Cqrs\QueryHandler;
use App\Infrastructure\Repository\ProductRepository;
use App\Presentation\Message\GetProductsQuery;

final class GetProductsQueryHandler implements QueryHandler
{
    public function __construct(private readonly ProductRepository $repo){}

    public function __invoke(GetProductsQuery $query)
    {
        /* read query is allowed to bypass aggregate and go directly to repo */
        $products = $this->repo->findFiveProductsByFilters($query->priceLessThan(), $query->category());
        $result = [];
        foreach ($products as $product) {
            $result[] = $product->toArray();
        }
        return $result;
    }
}
