<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car\Filters;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Car;

final class AndFilter implements CarFilter
{
    /**
     * @var CarFilter
     */
    private $leftFilter;

    /**
     * @var CarFilter
     */
    private $rightFilter;


    public function __construct(CarFilter $leftFilter, CarFilter $rightFilter)
    {
        $this->leftFilter = $leftFilter;
        $this->rightFilter = $rightFilter;
    }


    public function evaluate(Car $car): bool
    {
        return $this->leftFilter->evaluate($car) && $this->rightFilter->evaluate($car);
    }
}
