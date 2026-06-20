<?php

namespace App\OpenApi\Parameters;

use OpenApi\Attributes as OA;

class PaginationParameters
{
    #[OA\Parameter(
        parameter: 'PaginationPage',
        name: 'page',
        description: 'Número da página',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer', default: 1)
    )]
    public $page;

    #[OA\Parameter(
        parameter: 'PaginationPageSize',
        name: 'page_size',
        description: 'Itens por página',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer', default: 25)
    )]
    public $pageSize;
}
