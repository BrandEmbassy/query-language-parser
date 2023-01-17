<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\IsNotNull;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarHasColorFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage\CarQueryParserFactory;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * @final
 */
class IsNotNullQueryLanguageOperatorTest extends TestCase
{
    private const DO_NOT_USE_VALUE_ONLY_FILTER = false;


    /**
     * @dataProvider queryToBeParsedProvider
     *
     * @throws Throwable
     */
    public function testOperatorCanBeParsed(string $query): void
    {
        $parser = (new CarQueryParserFactory())->create(self::DO_NOT_USE_VALUE_ONLY_FILTER);

        $result = $parser->parse($query);

        Assert::assertInstanceOf(CarHasColorFilter::class, $result);
    }


    /**
     * @return string[][]
     */
    public function queryToBeParsedProvider(): array
    {
        return [
            ['query' => 'color IS NOT NULL'],
            ['query' => '  color     IS    NOT         NULL    '],
        ];
    }
}
