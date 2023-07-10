<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\In;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarBrandFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage\CarQueryParserFactory;
use BrandEmbassy\QueryLanguageParser\QueryParserContext;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Throwable;
use function assert;

/**
 * @final
 */
class InQueryLanguageOperatorTest extends TestCase
{
    /**
     * @dataProvider queryToBeParsedProvider
     *
     * @param string[] $expectedBrands
     *
     * @throws Throwable
     */
    public function testOperatorCanBeParsed(array $expectedBrands, string $query): void
    {
        $parser = (new CarQueryParserFactory())->create();

        $result = $parser->parse($query, new QueryParserContext());

        assert($result instanceof CarBrandFilter);
        Assert::assertSame($expectedBrands, $result->getBrands());
    }


    /**
     * @return mixed[]
     */
    public function queryToBeParsedProvider(): array
    {
        return [
            [
                'expectedBrands' => ['bmw', 'audi'],
                'query' => 'brand IN (bmw, audi)',
            ],
            [
                'expectedBrands' => ['bmw', 'audi'],
                'query' => '  brand    IN  (    bmw ,  audi  )   ',
            ],
            [
                'expectedBrands' => ['bmw'],
                'query' => 'brand IN (bmw)',
            ],
        ];
    }
}
