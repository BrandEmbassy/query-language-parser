<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\NotLike;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarBrandLikeFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\NotFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage\CarQueryParserFactory;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Throwable;
use function assert;

/**
 * @final
 */
class NotLikeSymbolQueryLanguageOperatorTest extends TestCase
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

        assert($result instanceof NotFilter);

        $subFilter = $result->getSubFilter();
        assert($subFilter instanceof CarBrandLikeFilter);
        Assert::assertSame('bmw', $subFilter->getBrand());
    }


    /**
     * @return string[][]
     */
    public function queryToBeParsedProvider(): array
    {
        return [
            ['query' => 'brand !~ bmw'],
            ['query' => 'brand!~bmw'],
            ['query' => 'brand !~bmw'],
            ['query' => 'brand!~ bmw'],
            ['query' => '  brand     !~          bmw    '],
        ];
    }
}
