<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use Ferno\Loco\ParseFailureException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @final
 */
class TextValueParserCreatorTest extends TestCase
{
    /**
     * @dataProvider validTextValueProvider
     */
    public function testParsingValidTextValue(string $expectedParsedResult, string $valueToParse): void
    {
        $parser = TextValueParserCreator::create();

        $actualValue = $parser->parse($valueToParse);

        Assert::assertSame($expectedParsedResult, $actualValue);
    }


    /**
     * @return string[][]
     */
    public function validTextValueProvider(): array
    {
        return [
            [
                'expectedParsedResult' => 'hello world',
                'valueToParse' => '"hello world"',
            ],
            [
                'expectedParsedResult' => 'foobar',
                'valueToParse' => '\'foobar\'',
            ],
            [
                'expectedParsedResult' => '1234',
                'valueToParse' => '"1234"',
            ],
        ];
    }


    /**
     * @dataProvider invalidTextValueProvider
     */
    public function testParsingInvalidTextValue(string $valueToParse): void
    {
        $parser = TextValueParserCreator::create();

        $this->expectException(ParseFailureException::class);

        $parser->parse($valueToParse);
    }


    /**
     * @return string[][]
     */
    public function invalidTextValueProvider(): array
    {
        return [
            ['valueToParse' => 'hello world'],
            ['valueToParse' => '"foo bar'],
            ['valueToParse' => 'foo bar\''],
            ['valueToParse' => '"foo bar\''],
        ];
    }
}
