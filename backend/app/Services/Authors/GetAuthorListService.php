<?php

namespace App\Services\Authors;

use App\DTOs\Authors\AuthorFiltersDTO;
use App\DTOs\OrderDTO;
use App\Repositories\Authors\AuthorRepository;

class GetAuthorListService
{
    public function __construct(
        private readonly AuthorRepository $repository
    ) {}

    public function execute(array $filters = [], array $order = [], int $pageSize = 25)
    {
        $filtersDTO = AuthorFiltersDTO::fromArray($filters);
        $ordersDTO = OrderDTO::fromArray($order);

        return $this->repository->getFilteredQuery($filtersDTO, $ordersDTO)->paginate($pageSize);
    }
}
