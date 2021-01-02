<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\AndFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarBrandFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarColorFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarHasColorFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\NotFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\OrFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage\CarQueryParserFactory;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use function assert;

final class QueryParserTest extends TestCase
{
    /**
     * @dataProvider queryToParseProvider
     *
     * @param callable $expectedFilterMatcher
     * @param string $query
     */
    public function testCaseQueryIsParsed(callable $expectedFilterMatcher, string $query): void
    {
        $parser = $this->createQueryParser();

        $actualFilter = $parser->parse($query);

        $expectedFilterMatcher($actualFilter);
    }


    public function queryToParseProvider(): array
    {
        return [
            'empty query' => [
                'expectedFilter' => static function (?CarFilter $filter): void {
                    Assert::assertNull($filter);
                },
                'query' => '',
            ],

            'basic filter' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    $this->assertCarBrandFilter(['bmw'], $filter);
                },
                'query' => 'brand=bmw',
            ],

            'basic filter with whitespaces around' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    $this->assertCarBrandFilter(['bmw'], $filter);
                },
                'query' => '   brand  =       bmw ',
            ],

            'and' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof AndFilter);

                    $this->assertCarBrandFilter(['bmw'], $filter->getLeftFilter());
                    $this->assertCarColorFilter(['yellow'], $filter->getRightFilter());
                },
                'query' => 'brand=bmw AND color=yellow',
            ],

            'multiple and' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof AndFilter);

                    $this->assertCarBrandFilter(['bmw'], $filter->getLeftFilter());

                    $rightSubFilter = $filter->getRightFilter();
                    assert($rightSubFilter instanceof AndFilter);

                    $this->assertCarColorFilter(['yellow'], $rightSubFilter->getLeftFilter());

                    $rightSubSubFilter = $rightSubFilter->getRightFilter();
                    assert($rightSubSubFilter instanceof NotFilter);
                    $this->assertCarBrandFilter(['audi'], $rightSubSubFilter->getSubFilter());
                },
                'query' => 'brand=bmw AND color=yellow AND brand!=audi',
            ],

            'or' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof OrFilter);

                    $this->assertCarBrandFilter(['bmw'], $filter->getLeftFilter());
                    $this->assertCarColorFilter(['yellow'], $filter->getRightFilter());
                },
                'query' => 'brand=bmw OR color=yellow',
            ],

            'multiple or' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof OrFilter);

                    $this->assertCarBrandFilter(['bmw'], $filter->getLeftFilter());

                    $rightSubFilter = $filter->getRightFilter();
                    assert($rightSubFilter instanceof OrFilter);

                    $this->assertCarColorFilter(['yellow'], $rightSubFilter->getLeftFilter());

                    $rightSubSubFilter = $rightSubFilter->getRightFilter();
                    assert($rightSubSubFilter instanceof NotFilter);
                    $this->assertCarBrandFilter(['audi'], $rightSubSubFilter->getSubFilter());
                },
                'query' => 'brand=bmw OR color=yellow OR brand!=audi',
            ],

            'and and or' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof AndFilter);

                    $this->assertCarBrandFilter(['bmw'], $filter->getLeftFilter());

                    $rightSubFilter = $filter->getRightFilter();
                    assert($rightSubFilter instanceof OrFilter);

                    $this->assertCarColorFilter(['yellow'], $rightSubFilter->getLeftFilter());

                    $rightSubSubFilter = $rightSubFilter->getRightFilter();
                    assert($rightSubSubFilter instanceof NotFilter);
                    $this->assertCarBrandFilter(['audi'], $rightSubSubFilter->getSubFilter());
                },
                'query' => 'brand=bmw AND color=yellow OR brand!=audi',
            ],

            'sub expression' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof OrFilter);

                    $rightSubFilter = $filter->getRightFilter();
                    assert($rightSubFilter instanceof NotFilter);
                    $this->assertCarBrandFilter(['audi'], $rightSubFilter->getSubFilter());

                    $leftSubFilter = $filter->getLeftFilter();
                    assert($leftSubFilter instanceof AndFilter);

                    $this->assertCarBrandFilter(['bmw'], $leftSubFilter->getLeftFilter());
                    $this->assertCarColorFilter(['yellow'], $leftSubFilter->getRightFilter());
                },
                'query' => '(brand=bmw AND color=yellow) OR brand!=audi',
            ],

            'sub expression #2' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof OrFilter);

                    $leftSubFilter = $filter->getLeftFilter();
                    assert($leftSubFilter instanceof AndFilter);

                    $this->assertCarBrandFilter(['bmw'], $leftSubFilter->getLeftFilter());
                    $this->assertCarColorFilter(['yellow'], $leftSubFilter->getRightFilter());

                    $rightSubFilter = $filter->getRightFilter();
                    assert($rightSubFilter instanceof AndFilter);

                    $this->assertCarBrandFilter(['audi'], $rightSubFilter->getLeftFilter());
                    $this->assertCarColorFilter(['black'], $rightSubFilter->getRightFilter());
                },
                'query' => '  ( brand=  bmw AND  color=yellow )  OR  (  brand=audi  AND color=black ) ',
            ],

            'not sub expression' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof NotFilter);
                    $this->assertCarBrandFilter(['bmw'], $filter->getSubFilter());
                },
                'query' => 'NOT (brand=bmw)',
            ],

            'not sub expression #2' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof OrFilter);

                    $leftSubFilter = $filter->getLeftFilter();
                    assert($leftSubFilter instanceof NotFilter);

                    $leftSubSubFilter = $leftSubFilter->getSubFilter();
                    assert($leftSubSubFilter instanceof AndFilter);

                    $this->assertCarBrandFilter(['bmw'], $leftSubSubFilter->getLeftFilter());
                    $this->assertCarColorFilter(['yellow'], $leftSubSubFilter->getRightFilter());

                    $rightSubFilter = $filter->getRightFilter();
                    assert($rightSubFilter instanceof AndFilter);

                    $this->assertCarBrandFilter(['audi'], $rightSubFilter->getLeftFilter());
                    $this->assertCarColorFilter(['black'], $rightSubFilter->getRightFilter());
                },
                'query' => '  NOT  ( brand= bmw AND  color=yellow) OR  (  brand=audi  AND color=black ) ',
            ],

            'in and and' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof AndFilter);

                    $this->assertCarBrandFilter(['bmw', 'audi'], $filter->getLeftFilter());
                    $this->assertCarColorFilter(['yellow'], $filter->getRightFilter());
                },
                'query' => 'brand IN (bmw, audi) AND color=yellow',
            ],

            'is null and and #1' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof AndFilter);

                    $this->assertCarBrandFilter(['bmw', 'audi'], $filter->getLeftFilter());

                    $rightSubFilter = $filter->getRightFilter();
                    assert($rightSubFilter instanceof NotFilter);
                    $this->assertCarHasColorFilter($rightSubFilter->getSubFilter());
                },
                'query' => 'brand IN (bmw, audi) AND color IS NULL',
            ],

            'is not null and and' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof AndFilter);

                    $this->assertCarHasColorFilter($filter->getLeftFilter());
                    $this->assertCarBrandFilter(['bmw', 'audi'], $filter->getRightFilter());
                },
                'query' => 'color IS NOT NULL AND brand IN (bmw, audi)',
            ],

            'is not null and and #2' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof AndFilter);

                    $this->assertCarHasColorFilter($filter->getLeftFilter());
                    $this->assertCarBrandFilter(['bmw', 'audi'], $filter->getRightFilter());
                },
                'query' => '  color     IS  NOT      NULL    AND   brand  IN    (       bmw ,         audi)     ',
            ],
        ];
    }


    /**
     * @dataProvider fieldsAndOperatorsToParseProvider
     *
     * @param string $query
     */
    public function testAllFieldsAndOperatorsCanBeParsed(string $query): void
    {
        $parser = $this->createQueryParser();

        $result = $parser->parse($query);

        Assert::assertInstanceOf(CarFilter::class, $result);
    }


    public function fieldsAndOperatorsToParseProvider(): array
    {
        return [
            /* ********** FIELDS ********** */
            ['query' => 'brand = bmw'],
            ['query' => 'color = yellow'],
            ['query' => 'numberOfDoors = 5'],

            /* ********** OPERATORS ********** */
            ['query' => 'brand = bmw'],
            ['query' => 'brand != bmw'],
            ['query' => 'brand IN (bmw, audi)'],
            ['query' => 'brand NOT IN (bmw, audi)'],
            ['query' => 'color IS NULL'],
            ['query' => 'color IS NOT NULL'],
            ['query' => 'numberOfDoors > 4'],
            ['query' => 'numberOfDoors >= 4'],
            ['query' => 'numberOfDoors < 4'],
            ['query' => 'numberOfDoors <= 4'],
        ];
    }


    private function createQueryParser(): QueryParser
    {
        return (new CarQueryParserFactory())->create();
    }


    /**
     * @param string[] $expectedBrands
     * @param CarFilter|null $filter
     */
    private function assertCarBrandFilter(array $expectedBrands, ?CarFilter $filter): void
    {
        assert($filter instanceof CarBrandFilter);
        Assert::assertSame($expectedBrands, $filter->getBrands());
    }


    /**
     * @param string[] $expectedColors
     * @param CarFilter|null $filter
     */
    private function assertCarColorFilter(array $expectedColors, ?CarFilter $filter): void
    {
        assert($filter instanceof CarColorFilter);
        Assert::assertSame($expectedColors, $filter->getColors());
    }


    private function assertCarHasColorFilter(?CarFilter $filter): void
    {
        assert($filter instanceof CarHasColorFilter);
    }
}
