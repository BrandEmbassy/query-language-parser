<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use Ferno\Loco\ParseFailureException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @final
 */
class StringValueParserCreatorTest extends TestCase
{
    /**
     * @dataProvider validStringValueProvider
     */
    public function testParsingValidStringValue(string $valueToParse): void
    {
        $parser = StringValueParserCreator::create();

        $actualValue = $parser->parse($valueToParse);

        Assert::assertSame($valueToParse, $actualValue);
    }


    /**
     * @return string[][]
     */
    public function validStringValueProvider(): array
    {
        return [
            ['valueToParse' => 'hello'],
            ['valueToParse' => 'foo-bar'],
            ['valueToParse' => 'Foo_Bar_1234'],
            ['valueToParse' => '<!F7:1F9@prod.o.com>'],
            ['valueToParse' => 'abcd+ef;gh'],
        ];
    }


    /**
     * @dataProvider invalidStringValueProvider
     */
    public function testParsingInvalidStringValue(string $valueToParse): void
    {
        $parser = StringValueParserCreator::create();

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
        ];
    }
}
