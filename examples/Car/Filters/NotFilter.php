<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car\Filters;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Car;

final class NotFilter implements CarFilter
{
    /**
     * @var CarFilter
     */
    private $subFilter;


    public function __construct(CarFilter $subFilter)
    {
        $this->subFilter = $subFilter;
    }


    public function getSubFilter(): CarFilter
    {
        return $this->subFilter;
    }


    public function evaluate(Car $car): bool
    {
        return !$this->subFilter->evaluate($car);
    }
}
