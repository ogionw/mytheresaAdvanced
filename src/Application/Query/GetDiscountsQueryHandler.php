<?php
declare(strict_types=1);

namespace App\Application\Query;
use App\Application\Cqrs\QueryHandler;
use App\Infrastructure\Repository\DiscountRepository;
use App\Presentation\Message\GetDiscountsQuery;

final class GetDiscountsQueryHandler implements QueryHandler
{
    public function __construct(private readonly DiscountRepository $repo){}

    public function __invoke(GetDiscountsQuery $query)
    {
        /* read query is allowed to bypass aggregate and go directly to repo */
        $discounts = $this->repo->findByFilters($query->sku(), $query->category());
        $result = [];
        foreach ($discounts as $discount) {
            $result[] = [$discount->getSku(), $discount->getCategory(), $discount->getValue()];
        }
        return $result;
    }
}
