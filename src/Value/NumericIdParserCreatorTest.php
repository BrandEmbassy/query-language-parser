<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use Ferno\Loco\ParseFailureException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class NumericIdParserCreatorTest extends TestCase
{
    /**
     * @dataProvider validNumericIdProvider
     */
    public function testParsingValidNumericId(int $expectedNumericId, string $valueToParse): void
    {
        $parser = NumericIdParserCreator::create();

        $actualNumericId = $parser->parse($valueToParse);

        Assert::assertSame($expectedNumericId, $actualNumericId);
    }


    /**
     * @return mixed[]
     */
    public function validNumericIdProvider(): array
    {
        return [
            [
                'expectedNumericId' => 1,
                'valueToParse' => '1',
            ],
            [
                'expectedNumericId' => 10,
                'valueToParse' => '10',
            ],
            [
                'expectedNumericId' => 158,
                'valueToParse' => '158',
            ],
            [
                'expectedNumericId' => 1234567890,
                'valueToParse' => '1234567890',
            ],
        ];
    }


    /**
     * @dataProvider invalidNumericIdProvider
     */
    public function testParsingInvalidNumericId(string $valueToParse): void
    {
        $parser = NumericIdParserCreator::create();

        $this->expectException(ParseFailureException::class);

        $parser->parse($valueToParse);
    }


    /**
     * @return mixed[]
     */
    public function invalidNumericIdProvider(): array
    {
        return [
            ['valueToParse' => '0'],
            ['valueToParse' => '-10'],
            ['valueToParse' => 'hello'],
            ['valueToParse' => 'foo 158'],
            ['valueToParse' => 'a158'],
            ['valueToParse' => '0158'],
        ];
    }
}
