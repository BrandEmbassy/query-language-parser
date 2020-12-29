<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\IsNotNull;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarHasColorFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage\CarQueryParserFactory;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class IsNotNullQueryLanguageOperatorTest extends TestCase
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

        Assert::assertInstanceOf(CarHasColorFilter::class, $result);
    }


    public function queryToBeParsedProvider(): array
    {
        return [
            ['query' => 'color IS NOT NULL'],
            ['query' => '  color     IS    NOT         NULL    '],
        ];
    }
}
