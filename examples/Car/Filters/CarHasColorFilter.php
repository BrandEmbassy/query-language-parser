<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car\Filters;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Car;

final class CarHasColorFilter implements CarFilter
{
    public function evaluate(Car $car): bool
    {
        return $car->hasColor();
    }
}
