<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car\Filters;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Car;

final class CarNumberOfDoorsLessThanOrEqualFilter implements CarFilter
{
    /**
     * @var int
     */
    private $numberOfDoors;


    public function __construct(int $numberOfDoors)
    {
        $this->numberOfDoors = $numberOfDoors;
    }


    public function getNumberOfDoors(): int
    {
        return $this->numberOfDoors;
    }


    public function evaluate(Car $car): bool
    {
        return $car->getNumberOfDoors() <= $this->numberOfDoors;
    }
}
