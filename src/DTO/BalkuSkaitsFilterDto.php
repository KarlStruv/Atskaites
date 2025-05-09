<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\HttpFoundation\Request;

final readonly class BalkuSkaitsFilterDto
{
    public function __construct(
        public ?string $startDate = null,
        public ?string $endDate = null,
        public int     $draw = 1
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            startDate: $request->query->get('startDate'),
            endDate: $request->query->get('endDate'),
            draw: $request->query->getInt('draw', 1)
        );
    }
}