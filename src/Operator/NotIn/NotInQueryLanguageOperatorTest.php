<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\NotIn;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarBrandFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\NotFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage\CarQueryParserFactory;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use function assert;

final class NotInQueryLanguageOperatorTest extends TestCase
{
    /**
     * @dataProvider queryToBeParsedProvider
     *
     * @param string[] $expectedBrands
     */
    public function testOperatorCanBeParsed(array $expectedBrands, string $query): void
    {
        $parser = (new CarQueryParserFactory())->create();

        $result = $parser->parse($query);

        assert($result instanceof NotFilter);
        $subFilter = $result->getSubFilter();

        assert($subFilter instanceof CarBrandFilter);
        Assert::assertSame($expectedBrands, $subFilter->getBrands());
    }


    /**
     * @return mixed[]
     */
    public function queryToBeParsedProvider(): array
    {
        return [
            [
                'expectedBrands' => ['bmw', 'audi'],
                'query' => 'brand NOT IN (bmw, audi)',
            ],
            [
                'expectedBrands' => ['bmw', 'audi'],
                'query' => '  brand    NOT     IN  (    bmw ,  audi  )   ',
            ],
            [
                'expectedBrands' => ['bmw'],
                'query' => 'brand NOT IN (bmw)',
            ],
        ];
    }
}
