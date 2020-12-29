<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car\Filters;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Car;
use function in_array;

final class CarNumberOfDoorsFilter implements CarFilter
{
    /**
     * @var int[]
     */
    private $numberOfDoors;


    /**
     * @param int[] $numberOfDoors
     */
    public function __construct(array $numberOfDoors)
    {
        $this->numberOfDoors = $numberOfDoors;
    }


    /**
     * @return int[]
     */
    public function getNumberOfDoors(): array
    {
        return $this->numberOfDoors;
    }


    public function evaluate(Car $car): bool
    {
        return in_array($car->getNumberOfDoors(), $this->numberOfDoors, true);
    }
}
