<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\NotEqualTo;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarBrandFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\NotFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage\CarQueryParserFactory;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class NotEqualToQueryLanguageOperatorTest extends TestCase
{
    /**
     * @dataProvider queryToBeParsedProvider
     *
     * @param string $query
     */
    public function testOperatorCanBeParsed(string $query): void
    {
        $parser = (new CarQueryParserFactory())->create();

        $result = $parser->parse($query);

        assert($result instanceof NotFilter);

        $subFilter = $result->getSubFilter();
        assert($subFilter instanceof CarBrandFilter);
        Assert::assertSame(['bmw'], $subFilter->getBrands());
    }


    /**
     * @return string[][]
     */
    public function queryToBeParsedProvider(): array
    {
        return [
            ['query' => 'brand != bmw'],
            ['query' => 'brand!=bmw'],
            ['query' => 'brand !=bmw'],
            ['query' => 'brand!= bmw'],
            ['query' => '  brand     !=          bmw    '],
        ];
    }
}
