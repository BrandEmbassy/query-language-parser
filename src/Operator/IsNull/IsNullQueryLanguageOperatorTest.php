<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\IsNull;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarHasColorFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\NotFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage\CarQueryParserFactory;
use BrandEmbassy\QueryLanguageParser\QueryParserContext;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Throwable;
use function assert;

/**
 * @final
 */
class IsNullQueryLanguageOperatorTest extends TestCase
{
    /**
     * @dataProvider queryToBeParsedProvider
     *
     * @throws Throwable
     */
    public function testOperatorCanBeParsed(string $query): void
    {
        $parser = (new CarQueryParserFactory())->create();

        $result = $parser->parse($query, new QueryParserContext());

        assert($result instanceof NotFilter);
        Assert::assertInstanceOf(CarHasColorFilter::class, $result->getSubFilter());
    }


    /**
     * @return string[][]
     */
    public function queryToBeParsedProvider(): array
    {
        return [
            ['query' => 'color IS NULL'],
            ['query' => '  color     IS          NULL    '],
        ];
    }
}
