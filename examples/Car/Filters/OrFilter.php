<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car\Filters;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Car;

final class OrFilter implements CarFilter
{
    private CarFilter $leftFilter;

    private CarFilter $rightFilter;


    public function __construct(CarFilter $leftFilter, CarFilter $rightFilter)
    {
        $this->leftFilter = $leftFilter;
        $this->rightFilter = $rightFilter;
    }


    public function getLeftFilter(): CarFilter
    {
        return $this->leftFilter;
    }


    public function getRightFilter(): CarFilter
    {
        return $this->rightFilter;
    }


    public function evaluate(Car $car): bool
    {
        return $this->leftFilter->evaluate($car) || $this->rightFilter->evaluate($car);
    }
}
