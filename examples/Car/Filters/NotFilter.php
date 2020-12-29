<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car\Filters;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Car;

final class NotFilter implements CarFilter
{
    /**
     * @var CarFilter
     */
    private $subCondition;


    public function __construct(CarFilter $subCondition)
    {
        $this->subCondition = $subCondition;
    }


    public function evaluate(Car $car): bool
    {
        return !$this->subCondition->evaluate($car);
    }
}
