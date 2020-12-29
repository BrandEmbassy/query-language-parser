<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car\Filters;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Car;
use function in_array;

final class CarBrandFilter implements CarFilter
{
    /**
     * @var string[]
     */
    private $brands;


    /**
     * @param string[] $brands
     */
    public function __construct(array $brands)
    {
        $this->brands = $brands;
    }


    public function evaluate(Car $car): bool
    {
        return in_array($car->getBrand(), $this->brands, true);
    }
}
