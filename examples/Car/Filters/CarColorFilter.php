<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car\Filters;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Car;
use function in_array;

final class CarColorFilter implements CarFilter
{
    /**
     * @var string[]
     */
    private $colors;


    public function __construct(array $colors)
    {
        $this->colors = $colors;
    }


    /**
     * @return string[]
     */
    public function getColors(): array
    {
        return $this->colors;
    }


    public function evaluate(Car $car): bool
    {
        return in_array($car->getColor(), $this->colors, true);
    }
}
