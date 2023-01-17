<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use Ferno\Loco\GrammarException;
use Ferno\Loco\ParseFailureException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @final
 */
class ValueOnlyParserCreatorTest extends TestCase
{
    /**
     * @dataProvider validStringValueProvider
     *
     * @throws GrammarException
     * @throws ParseFailureException
     */
    public function testParsingSucceeded(string $expectedValue, string $valueToParse): void
    {
        $parser = ValueOnlyParserCreator::create();

        $actualValue = $parser->parse($valueToParse);

        Assert::assertSame($expectedValue, $actualValue);
    }


    /**
     * @return string[][]
     */
    public function validStringValueProvider(): array
    {
        return [
            [
                'expectedValue' => 'hello',
                'valueToParse' => 'hello',
            ],
            [
                'expectedValue' => 'foo-bar',
                'valueToParse' => 'foo-bar',
            ],
            [
                'expectedValue' => 'Foo_Bar_1234',
                'valueToParse' => 'Foo_Bar_1234',
            ],
            [
                'expectedValue' => '!F7:1F9@prod.o.com',
                'valueToParse' => '!F7:1F9@prod.o.com',
            ],
            [
                'expectedValue' => 'abcd+ef;gh',
                'valueToParse' => 'abcd+ef;gh',
            ],
            [
                'expectedValue' => 'hello',
                'valueToParse' => '"hello"',
            ],
            [
                'expectedValue' => 'foo-bar',
                'valueToParse' => '"foo-bar"',
            ],
            [
                'expectedValue' => 'Foo_Bar_1234',
                'valueToParse' => '"Foo_Bar_1234"',
            ],
            [
                'expectedValue' => '!F7:1F9@prod.o.com',
                'valueToParse' => '"!F7:1F9@prod.o.com"',
            ],
            [
                'expectedValue' => 'abcd+ef;gh',
                'valueToParse' => '"abcd+ef;gh"',
            ],
            [
                'expectedValue' => 'Something with spaces',
                'valueToParse' => '"Something with spaces"',
            ],
        ];
    }


    /**
     * @dataProvider invalidStringValueProvider
     *
     * @throws GrammarException
     * @throws ParseFailureException
     */
    public function testParsingFailed(string $valueToParse): void
    {
        $parser = ValueOnlyParserCreator::create();

        $this->expectException(ParseFailureException::class);

        $parser->parse($valueToParse);
    }


    /**
     * @return string[][]
     */
    public function invalidStringValueProvider(): array
    {
        return [
            ['valueToParse' => 'hello world'],
            ['valueToParse' => ' foobar'],
            ['valueToParse' => 'foobar=test'],
            ['valueToParse' => 'foobar,'],
            ['valueToParse' => 'foobar('],
            ['valueToParse' => 'foobar)'],
            ['valueToParse' => 'foobar='],
            ['valueToParse' => 'foobar<'],
            ['valueToParse' => 'foobar>'],
        ];
    }
}
