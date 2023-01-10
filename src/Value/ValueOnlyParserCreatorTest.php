<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use BrandEmbassy\QueryLanguageParser\Field\ValueOnlyQueryLanguageField;
use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;
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
    public function testParsingSucceeded(string $valueToParse): void
    {
        $filter = $this->createFilter();
        $parser = ValueOnlyParserCreator::create($filter);

        $actualValue = $parser->parse($valueToParse);

        Assert::assertSame($valueToParse . '_filter', $actualValue);
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
            ['valueToParse' => '!F7:1F9@prod.o.com'],
            ['valueToParse' => 'abcd+ef;gh'],
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
        $filter = $this->createFilter();
        $parser = ValueOnlyParserCreator::create($filter);

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


    private function createFilter(): ValueOnlyQueryLanguageField
    {
        return new class () implements ValueOnlyQueryLanguageField {
            public function getFieldIdentifier(): string
            {
                return 'valueOnly';
            }


            public function getFieldNameParserIdentifier(): string
            {
                return 'valueOnly.fieldName';
            }


            public function createFieldNameParser(): MonoParser
            {
                return StringValueParserCreator::create();
            }


            public function createFilter(string $value): string
            {
                return $value . '_filter';
            }
        };
    }
}
