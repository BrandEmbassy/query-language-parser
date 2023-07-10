<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarBrandFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage\CarQueryParserFactory;
use BrandEmbassy\QueryLanguageParser\QueryParserContext;
use BrandEmbassy\QueryLanguageParser\UnableToParseQueryException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Throwable;
use function assert;

/**
 * @final
 */
class MultipleValuesExpressionParserCreatorTest extends TestCase
{
    /**
     * @dataProvider validMultipleValueExpressionProvider
     *
     * @param string[] $expectedValues
     *
     * @throws Throwable
     */
    public function testParsingValidMultipleValueExpression(
        array $expectedValues,
        string $multipleValueExpression
    ): void {
        $parser = (new CarQueryParserFactory())->create();
        $valueToParse = 'brand IN ' . $multipleValueExpression;

        $result = $parser->parse($valueToParse, new QueryParserContext());

        assert($result instanceof CarBrandFilter);
        Assert::assertSame($expectedValues, $result->getBrands());
    }


    /**
     * @return mixed[]
     */
    public function validMultipleValueExpressionProvider(): array
    {
        return [
            [
                'expectedValues' => ['bmw'],
                'multipleValueExpression' => '(bmw)',
            ],
            [
                'expectedValues' => ['bmw'],
                'multipleValueExpression' => '(   bmw      )',
            ],
            [
                'expectedValues' => ['bmw', 'audi'],
                'multipleValueExpression' => '(bmw, audi)',
            ],
            [
                'expectedValues' => ['bmw', 'audi'],
                'multipleValueExpression' => '(    bmw  ,    audi   )',
            ],
            [
                'expectedValues' => ['bmw', 'audi', 'forever'],
                'multipleValueExpression' => '(bmw, audi, forever)',
            ],
            [
                'expectedValues' => ['bmw', 'audi', 'skoda'],
                'multipleValueExpression' => '(    bmw ,    audi      ,  skoda         )',
            ],
        ];
    }


    /**
     * @dataProvider invalidMultipleValueExpressionProvider
     */
    public function testParsingInvalidMultipleValueExpression(string $multipleValueExpression): void
    {
        $parser = (new CarQueryParserFactory())->create();
        $valueToParse = 'brand IN ' . $multipleValueExpression;

        $this->expectException(UnableToParseQueryException::class);

        $parser->parse($valueToParse, new QueryParserContext());
    }


    /**
     * @return string[][]
     */
    public function invalidMultipleValueExpressionProvider(): array
    {
        return [
            ['multipleValueExpression' => '()'],
            ['multipleValueExpression' => '(foo, bar,)'],
            ['multipleValueExpression' => '(foo, bar'],
            ['multipleValueExpression' => 'foo, bar)'],
            ['multipleValueExpression' => '(foo bar)'],
            ['multipleValueExpression' => 'hello world'],
            ['multipleValueExpression' => '158'],
        ];
    }
}
