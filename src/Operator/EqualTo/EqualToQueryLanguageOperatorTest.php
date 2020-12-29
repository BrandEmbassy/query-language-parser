<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\EqualTo;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarBrandFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage\CarQueryParserFactory;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class EqualToQueryLanguageOperatorTest extends TestCase
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

        assert($result instanceof CarBrandFilter);
        Assert::assertSame(['bmw'], $result->getBrands());
    }


    public function queryToBeParsedProvider(): array
    {
        return [
            ['query' => 'brand = bmw'],
            ['query' => 'brand=bmw'],
            ['query' => 'brand =bmw'],
            ['query' => 'brand= bmw'],
            ['query' => '  brand     =          bmw    '],
        ];
    }
}
