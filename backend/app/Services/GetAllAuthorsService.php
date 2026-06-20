<?php

namespace App\Services;

use App\DTOs\AuthorFiltersDTO;
use App\DTOs\OrderDTO;
use App\Repositories\AuthorRepository;

class GetAllAuthorsService
{
    public function __construct(
        private readonly AuthorRepository $repository
    ) {}

    public function execute(array $filters = [], array $order = [])
    {
        $filtersDTO = AuthorFiltersDTO::fromArray($filters);
        $ordersDTO = OrderDTO::fromArray($order);

        return $this->repository->getFilteredQuery($filtersDTO, $ordersDTO)->get();
    }
}
