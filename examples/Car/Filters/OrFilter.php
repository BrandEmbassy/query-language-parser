<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car\Filters;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Car;

final class OrFilter implements CarFilter
{
    /**
     * @var CarFilter
     */
    private $leftCondition;

    /**
     * @var CarFilter
     */
    private $rightCondition;


    public function __construct(CarFilter $leftCondition, CarFilter $rightCondition)
    {
        $this->leftCondition = $leftCondition;
        $this->rightCondition = $rightCondition;
    }


    public function evaluate(Car $car): bool
    {
        return $this->leftCondition->evaluate($car) || $this->rightCondition->evaluate($car);
    }
}
