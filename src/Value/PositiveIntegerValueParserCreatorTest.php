<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use Ferno\Loco\ParseFailureException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @final
 */
class PositiveIntegerValueParserCreatorTest extends TestCase
{
    /**
     * @dataProvider validPositiveIntegerValueProvider
     */
    public function testParsingValidPositiveIntegerValue(int $expectedValue, string $valueToParse): void
    {
        $parser = PositiveIntegerValueParserCreator::create();

        $actualValue = $parser->parse($valueToParse);

        Assert::assertSame($expectedValue, $actualValue);
    }


    /**
     * @return mixed[]
     */
    public function validPositiveIntegerValueProvider(): array
    {
        return [
            [
                'expectedValue' => 1,
                'valueToParse' => '1',
            ],
            [
                'expectedValue' => 10,
                'valueToParse' => '10',
            ],
            [
                'expectedValue' => 158,
                'valueToParse' => '158',
            ],
            [
                'expectedValue' => 1234567890,
                'valueToParse' => '1234567890',
            ],
            [
                'expectedValue' => 2147483647,
                'valueToParse' => '2147483647',
            ],
        ];
    }


    /**
     * @dataProvider invalidPositiveIntegerValueProvider
     */
    public function testParsingInvalidPositiveIntegerValue(string $valueToParse): void
    {
        $parser = PositiveIntegerValueParserCreator::create();

        $this->expectException(ParseFailureException::class);

        $parser->parse($valueToParse);
    }


    /**
     * @return mixed[]
     */
    public function invalidPositiveIntegerValueProvider(): array
    {
        return [
            ['valueToParse' => '0'],
            ['valueToParse' => '-10'],
            ['valueToParse' => 'hello'],
            ['valueToParse' => 'foo 158'],
            ['valueToParse' => 'a158'],
            ['valueToParse' => '0158'],
            ['valueToParse' => '2147483648'],
        ];
    }
}
