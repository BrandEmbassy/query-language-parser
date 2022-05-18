<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\Like;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarBrandLikeFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage\CarQueryParserFactory;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use function assert;

/**
 * @final
 */
class LikeSymbolQueryLanguageOperatorTest extends TestCase
{
    /**
     * @dataProvider queryToBeParsedProvider
     */
    public function testOperatorCanBeParsed(string $query): void
    {
        $parser = (new CarQueryParserFactory())->create();

        $result = $parser->parse($query);

        assert($result instanceof CarBrandLikeFilter);
        Assert::assertSame('bmw', $result->getBrand());
    }


    /**
     * @return string[][]
     */
    public function queryToBeParsedProvider(): array
    {
        return [
            ['query' => 'brand ~ bmw'],
            ['query' => 'brand~bmw'],
            ['query' => 'brand ~bmw'],
            ['query' => 'brand~ bmw'],
            ['query' => '  brand     ~          bmw    '],
        ];
    }
}
