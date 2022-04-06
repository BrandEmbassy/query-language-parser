<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use Ferno\Loco\ParseFailureException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @final
 */
class NumericValueParserCreatorTest extends TestCase
{
    /**
     * @dataProvider validNumericValueProvider
     *
     * @param mixed $expectedValue
     */
    public function testParsingValidNumericValue($expectedValue, string $valueToParse): void
    {
        $parser = NumericValueParserCreator::create();

        $actualValue = $parser->parse($valueToParse);

        Assert::assertSame($expectedValue, $actualValue);
    }


    /**
     * @return mixed[]
     */
    public function validNumericValueProvider(): array
    {
        return [
            [
                'expectedValue' => 0,
                'valueToParse' => '0',
            ],
            [
                'expectedValue' => 1,
                'valueToParse' => '1',
            ],
            [
                'expectedValue' => -1,
                'valueToParse' => '-1',
            ],
            [
                'expectedValue' => -1.58,
                'valueToParse' => '-1.58',
            ],
            [
                'expectedValue' => 10,
                'valueToParse' => '10',
            ],
            [
                'expectedValue' => 1234567890,
                'valueToParse' => '1234567890',
            ],
            [
                'expectedValue' => -1483.765,
                'valueToParse' => '-1483.765',
            ],
        ];
    }


    /**
     * @dataProvider invalidNumericValueProvider
     */
    public function testParsingInvalidNumericValue(string $valueToParse): void
    {
        $parser = NumericValueParserCreator::create();

        $this->expectException(ParseFailureException::class);

        $parser->parse($valueToParse);
    }


    /**
     * @return mixed[]
     */
    public function invalidNumericValueProvider(): array
    {
        return [
            ['valueToParse' => 'hello'],
            ['valueToParse' => 'foo 158'],
            ['valueToParse' => 'foo-158'],
            ['valueToParse' => 'a158'],
            ['valueToParse' => '-148 765'],
        ];
    }
}
