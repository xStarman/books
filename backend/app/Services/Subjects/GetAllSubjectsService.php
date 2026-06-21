<?php

namespace App\Services\Subjects;

use App\DTOs\Subjects\SubjectFiltersDTO;
use App\DTOs\OrderDTO;
use App\Repositories\Subjects\SubjectRepository;

class GetAllSubjectsService
{
    public function __construct(
        private readonly SubjectRepository $repository
    ) {}

    public function execute(array $filters = [], array $order = [])
    {
        $filtersDTO = SubjectFiltersDTO::fromArray($filters);
        $ordersDTO = OrderDTO::fromArray($order);

        return $this->repository->getFilteredQuery($filtersDTO, $ordersDTO)->get();
    }
}
