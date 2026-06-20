<?php

namespace App\DTOs;

class OrderDTO
{
    public function __construct(
        public string $field,
        public string $direction
    ) {}

    /**
     * @param array $orderArray e.g. ['Titulo' => 'asc']
     * @return OrderDTO[]
     */
    public static function fromArray(array $orderArray): array
    {
        $dtos = [];
        foreach ($orderArray as $field => $direction) {
            $dtos[] = new self($field, $direction);
        }
        return $dtos;
    }
}
