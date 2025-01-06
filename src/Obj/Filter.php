<?php

namespace App\Obj;

class Filter
{
    public ?string $search;
    public ?float $minPrice;
    public ?float $maxPrice;
    public ?array $categories;
    public ?array $tags;
    public ?array $sizes;
}