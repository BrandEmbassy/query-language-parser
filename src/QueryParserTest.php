<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\AndFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarBrandFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarBrandLikeFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarColorFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarColorLikeFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarHasColorFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\NotFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\OrFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage\CarBrandValueOnlyFilterFactory;
use BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage\CarQueryParserFactory;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Throwable;
use function assert;

/**
 * @final
 */
class QueryParserTest extends TestCase
{
    /**
     * @dataProvider queryToParseProvider
     *
     * @throws Throwable
     */
    public function testCaseQueryIsParsed(callable $expectedFilterMatcher, string $query): void
    {
        $parser = $this->createQueryParser();

        //$actualFilter = $parser->parse($query);
        $actualFilter = $parser->parse($query, new CarBrandValueOnlyFilterFactory());

        $expectedFilterMatcher($actualFilter);
    }


    /**
     * @return mixed[]
     */
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

            'basic filter double quoted' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    $this->assertCarBrandFilter(['Alfa romeo'], $filter);
                },
                'query' => 'brand = "Alfa romeo"',
            ],

            'value only' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    $this->assertCarBrandFilter(['BMW'], $filter);
                },
                'query' => 'BMW',
            ],

            'quoted value only' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    $this->assertCarBrandFilter(['Alfa romeo'], $filter);
                },
                'query' => '"Alfa romeo"',
            ],

            'AND' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof AndFilter);

                    $this->assertCarBrandFilter(['bmw'], $filter->getLeftFilter());
                    $this->assertCarColorFilter(['yellow'], $filter->getRightFilter());
                },
                'query' => 'brand=bmw AND color=yellow',
            ],

            'multiple AND' => [
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

            'OR' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof OrFilter);

                    $this->assertCarBrandFilter(['bmw'], $filter->getLeftFilter());
                    $this->assertCarColorFilter(['yellow'], $filter->getRightFilter());
                },
                'query' => 'brand=bmw OR color=yellow',
            ],

            'multiple OR' => [
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

            'AND and OR' => [
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

            'NOT sub expression' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof NotFilter);
                    $this->assertCarBrandFilter(['bmw'], $filter->getSubFilter());
                },
                'query' => 'NOT (brand=bmw)',
            ],

            'NOT sub expression #2' => [
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

            'IN and AND' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof AndFilter);

                    $this->assertCarBrandFilter(['bmw', 'audi'], $filter->getLeftFilter());
                    $this->assertCarColorFilter(['yellow'], $filter->getRightFilter());
                },
                'query' => 'brand IN (bmw, audi) AND color=yellow',
            ],

            'IS NULL and AND #1' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof AndFilter);

                    $this->assertCarBrandFilter(['bmw', 'audi'], $filter->getLeftFilter());

                    $rightSubFilter = $filter->getRightFilter();
                    assert($rightSubFilter instanceof NotFilter);
                    $this->assertCarHasColorFilter($rightSubFilter->getSubFilter());
                },
                'query' => 'brand IN (bmw, audi) AND color IS NULL',
            ],

            'IS NOT NULL and AND' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof AndFilter);

                    $this->assertCarHasColorFilter($filter->getLeftFilter());
                    $this->assertCarBrandFilter(['bmw', 'audi'], $filter->getRightFilter());
                },
                'query' => 'color IS NOT NULL AND brand IN (bmw, audi)',
            ],

            'IS NOT NULL and AND #2' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof AndFilter);

                    $this->assertCarHasColorFilter($filter->getLeftFilter());
                    $this->assertCarBrandFilter(['bmw', 'audi'], $filter->getRightFilter());
                },
                'query' => '  color     IS  NOT      NULL    AND   brand  IN    (       bmw ,         audi)     ',
            ],

            'LIKE and AND and NOT LIKE' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof AndFilter);

                    $leftFilter = $filter->getLeftFilter();
                    assert($leftFilter instanceof CarColorLikeFilter);

                    $rightFilter = $filter->getRightFilter();
                    assert($rightFilter instanceof NotFilter);

                    $this->assertCarColorLikeFilter('ello', $leftFilter);
                    $this->assertCarBrandLikeFilter('mw', $rightFilter->getSubFilter());
                },
                'query' => '  color   LIKE   ello    AND   brand  NOT LIKE    mw      ',
            ],

            'LIKE symbol and AND and NOT LIKE symbol' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    assert($filter instanceof AndFilter);

                    $leftFilter = $filter->getLeftFilter();
                    assert($leftFilter instanceof CarColorLikeFilter);

                    $rightFilter = $filter->getRightFilter();
                    assert($rightFilter instanceof NotFilter);

                    $this->assertCarColorLikeFilter('ello', $leftFilter);
                    $this->assertCarBrandLikeFilter('mw', $rightFilter->getSubFilter());
                },
                'query' => '  color      ~   ello    AND   brand  !~    mw      ',
            ],
        ];
    }


    /**
     * @dataProvider fieldsAndOperatorsToParseProvider
     *
     * @throws Throwable
     */
    public function testAllFieldsAndOperatorsCanBeParsed(string $query): void
    {
        $parser = $this->createQueryParser();

        $result = $parser->parse($query);

        Assert::assertInstanceOf(CarFilter::class, $result);
    }


    /**
     * @return string[][]
     */
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
            ['query' => 'brand ~ bmw'],
            ['query' => 'brand !~ bmw'],
            ['query' => 'brand LIKE bmw'],
            ['query' => 'brand NOT LIKE bmw'],
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
     */
    private function assertCarBrandFilter(array $expectedBrands, ?CarFilter $filter): void
    {
        assert($filter instanceof CarBrandFilter);
        Assert::assertSame($expectedBrands, $filter->getBrands());
    }


    /**
     * @param string[] $expectedColors
     */
    private function assertCarColorFilter(array $expectedColors, ?CarFilter $filter): void
    {
        assert($filter instanceof CarColorFilter);
        Assert::assertSame($expectedColors, $filter->getColors());
    }


    private function assertCarBrandLikeFilter(string $expectedBrand, ?CarFilter $filter): void
    {
        assert($filter instanceof CarBrandLikeFilter);
        Assert::assertSame($expectedBrand, $filter->getBrand());
    }


    private function assertCarColorLikeFilter(string $expectedBrand, ?CarFilter $filter): void
    {
        assert($filter instanceof CarColorLikeFilter);
        Assert::assertSame($expectedBrand, $filter->getColor());
    }


    private function assertCarHasColorFilter(?CarFilter $filter): void
    {
        assert($filter instanceof CarHasColorFilter);
    }
}
