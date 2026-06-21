<?php

namespace App\Services\Books;

use App\DTOs\Books\BookFiltersDTO;
use App\DTOs\OrderDTO;
use App\Repositories\Books\BookRepository;

class GetBookListService
{
    public function __construct(
        private readonly BookRepository $repository
    ) {}

    public function execute(array $filters = [], array $order = [], int $pageSize = 25)
    {
        $filtersDTO = BookFiltersDTO::fromArray($filters);
        $ordersDTO = OrderDTO::fromArray($order);

        return $this->repository->getFilteredQuery($filtersDTO, $ordersDTO)->paginate($pageSize);
    }
}
