<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\AndFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarBrandFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarBrandLikeFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarColorFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarColorLikeFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarHasColorFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarNumberOfDoorsFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\NotFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\OrFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage\CarQueryParserFactory;
use Generator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Throwable;
use function array_merge;
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
    public function testCaseQueryIsParsed(
        callable $expectedFilterMatcher,
        string $query,
        bool $useValueOnlyFilter
    ): void {
        $parser = $this->createQueryParser($useValueOnlyFilter);

        $actualFilter = $parser->parse($query, new QueryParserContext());

        $expectedFilterMatcher($actualFilter);
    }


    /**
     * @return Generator<string, array<string, mixed>>
     */
    public function queryToParseProvider(): Generator
    {
        $basicScenarios = [
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

            'Query parser context is used' => [
                'expectedFilter' => function (?CarFilter $filter): void {
                    $this->assertCarBrandFilter(['Alfa romeo'], $filter);
                },
                'query' => 'brand = "Alfa romeo"',
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
        $valueOnlyScenarios = [
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
        ];

        foreach ([true, false] as $useValueOnlyFilter) {
            $scenarios = $useValueOnlyFilter
                ? array_merge($basicScenarios, $valueOnlyScenarios)
                : $basicScenarios;
            foreach ($scenarios as $scenarioName => $scenario) {
                $scenarioName .= ($useValueOnlyFilter ? ' (supporting value only filter)' : '');

                yield $scenarioName => array_merge(
                    $scenario,
                    [
                        'useValueOnlyFilter' => $useValueOnlyFilter,
                    ],
                );
            }
        }
    }


    /**
     * @dataProvider fieldsAndOperatorsToParseProvider
     *
     * @throws Throwable
     */
    public function testAllFieldsAndOperatorsCanBeParsed(string $query, bool $useValueOnlyFilter): void
    {
        $parser = $this->createQueryParser($useValueOnlyFilter);

        $result = $parser->parse($query, new QueryParserContext());

        Assert::assertInstanceOf(CarFilter::class, $result);
    }


    /**
     * @return Generator<string, array<string, mixed>>
     */
    public function fieldsAndOperatorsToParseProvider(): Generator
    {
        $queries = [
            /* ********** FIELDS ********** */
            'brand = bmw',
            'color = yellow',
            'numberOfDoors = 5',

            /* ********** OPERATORS ********** */
            'brand = audi',
            'brand != bmw',
            'brand ~ bmw',
            'brand !~ bmw',
            'brand LIKE bmw',
            'brand NOT LIKE bmw',
            'brand IN (bmw, audi)',
            'brand NOT IN (bmw, audi)',
            'color IS NULL',
            'color IS NOT NULL',
            'numberOfDoors > 4',
            'numberOfDoors >= 4',
            'numberOfDoors < 4',
            'numberOfDoors <= 4',
        ];

        foreach ([true, false] as $useValueOnlyFilter) {
            foreach ($queries as $query) {
                $scenarioName = $query . ($useValueOnlyFilter ? ' / supporting value only filter' : '');

                yield $scenarioName => [
                    'query' => $query,
                    'useValueOnlyFilter' => $useValueOnlyFilter,
                ];
            }
        }
    }


    /**
     * @throws Throwable
     */
    public function testQueryParserIsUsingContext(): void
    {
        $parser = $this->createQueryParser(false);
        $context = new QueryParserContext();
        $context->set('number_of_doors_modifier', 3);

        $parsedFilter = $parser->parse('numberOfDoors = 5', $context);

        Assert::assertInstanceOf(CarNumberOfDoorsFilter::class, $parsedFilter);
        Assert::assertSame([8], $parsedFilter->getNumberOfDoors());
    }


    private function createQueryParser(bool $useValueOnlyFilter): QueryParser
    {
        if ($useValueOnlyFilter) {
            return (new CarQueryParserFactory())->createWithValueOnlyTermSupport();
        }

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
