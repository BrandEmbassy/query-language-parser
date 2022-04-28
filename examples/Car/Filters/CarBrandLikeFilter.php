<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car\Filters;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Car;
use function strpos;

final class CarBrandLikeFilter implements CarFilter
{
    private string $brand;


    public function __construct(string $brand)
    {
        $this->brand = $brand;
    }


    public function getBrand(): string
    {
        return $this->brand;
    }


    public function evaluate(Car $car): bool
    {
        return strpos($car->getBrand(), $this->brand) !== false;
    }
}
