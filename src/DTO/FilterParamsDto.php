<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\HttpFoundation\Request;

final readonly class FilterParamsDto
{
    public function __construct(
        public int    $start = 0,
        public int    $length = 10,
        public int    $draw = 1,
        public string $searchValue = '',
        public string $orderColumn = 'id',
        public string $orderDirection = 'asc',
        public array  $filters = [],
    )
    {
    }

    public static function fromRequest(Request $request): self
    {
        $search = $request->query->all('search');
        $searchValue = $search['value'] ?? '';

        $order = $request->query->all('order')[0] ?? ['column' => 0, 'dir' => 'asc'];
        $columns = $request->query->all('columns');
        $orderColumnIndex = $order['column'] ?? 0;
        $orderDirection = $order['dir'] ?? 'asc';
        $orderColumn = $columns[$orderColumnIndex]['data'] ?? $request->query->get('orderColumn', 'id');

        $filters = [
            'uzm_tips_vmf' => $request->query->get('uzm_tips_vmf'),
            'tilp_bruto_min' => $request->query->get('tilp_bruto_min'),
            'tilp_bruto_max' => $request->query->get('tilp_bruto_max'),
            'tilp_neto_min' => $request->query->get('tilp_neto_min'),
            'tilp_neto_max' => $request->query->get('tilp_neto_max'),
            'tilp_brakis_min' => $request->query->get('tilp_brakis_min'),
            'tilp_brakis_max' => $request->query->get('tilp_brakis_max'),
        ];

        return new self(
            start: $request->query->getInt('start'),
            length: $request->query->getInt('length', 10),
            draw: $request->query->getInt('draw', 1),
            searchValue: $searchValue,
            orderColumn: $orderColumn,
            orderDirection: $orderDirection,
            filters: $filters
        );
    }
}