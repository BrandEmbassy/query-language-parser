<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car\Filters;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Car;
use function strpos;

final class CarColorLikeFilter implements CarFilter
{
    private string $color;


    public function __construct(string $color)
    {
        $this->color = $color;
    }


    public function getColor(): string
    {
        return $this->color;
    }


    public function evaluate(Car $car): bool
    {
        return strpos($car->getColor(), $this->color) !== false;
    }
}
