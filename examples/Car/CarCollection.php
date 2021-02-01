<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car;

use Assert\Assertion;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarFilter;
use function array_filter;

final class CarCollection
{
    /**
     * @var array<int, Car>
     */
    private $cars;


    /**
     * @param array<int, Car> $cars
     */
    public function __construct(array $cars)
    {
        Assertion::allIsInstanceOf($cars, Car::class);
        $this->cars = $cars;
    }


    public function filter(CarFilter $filter): CarCollection
    {
        $filteredCars = array_filter(
            $this->cars,
            static function (Car $car) use ($filter): bool {
                return $filter->evaluate($car);
            }
        );

        return new CarCollection($filteredCars);
    }


    /**
     * @return array<int, Car>
     */
    public function toArray(): array
    {
        return $this->cars;
    }
}
