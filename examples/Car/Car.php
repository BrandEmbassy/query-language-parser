<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car;

final class Car
{
    /**
     * @var string
     */
    private $brand;

    /**
     * @var int
     */
    private $numberOfDoors;

    /**
     * @var string|null
     */
    private $color;


    public function __construct(string $brand, int $numberOfDoors, ?string $color = null)
    {
        $this->brand = $brand;
        $this->numberOfDoors = $numberOfDoors;
        $this->color = $color;
    }


    public function getBrand(): string
    {
        return $this->brand;
    }


    public function getNumberOfDoors(): int
    {
        return $this->numberOfDoors;
    }


    public function getColor(): string
    {
        return $this->color;
    }


    public function hasColor(): bool
    {
        return $this->color !== null;
    }
}
