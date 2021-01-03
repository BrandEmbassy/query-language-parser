<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use Ferno\Loco\ParseFailureException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use function assert;

final class UuidParserCreatorTest extends TestCase
{
    /**
     * @dataProvider validUuidProvider
     */
    public function testParsingValidUuid(string $valueToParse): void
    {
        $parser = UuidParserCreator::create();

        $actualValue = $parser->parse($valueToParse);

        assert($actualValue instanceof UuidInterface);
        Assert::assertSame($valueToParse, $actualValue->toString());
    }


    /**
     * @return string[][]
     */
    public function validUuidProvider(): array
    {
        return [
            ['valueToParse' => 'b0e7fdad-c229-4f4e-9c97-57b04754533e'],
            ['valueToParse' => '38db19e1-bf91-4c0b-9a33-51a8a1224494'],
            ['valueToParse' => 'cc89cf30-dbbe-43b7-a163-69be8e2f146f'],
        ];
    }


    /**
     * @dataProvider invalidUuidProvider
     */
    public function testParsingInvalidUuid(string $valueToParse): void
    {
        $parser = UuidParserCreator::create();

        $this->expectException(ParseFailureException::class);

        $parser->parse($valueToParse);
    }


    /**
     * @return string[][]
     */
    public function invalidUuidProvider(): array
    {
        return [
            ['valueToParse' => 'hello world'],
            ['valueToParse' => 'foo#bar'],
            ['valueToParse' => 'b0e7fdad-c229-4f4e-9c97'],
        ];
    }
}
