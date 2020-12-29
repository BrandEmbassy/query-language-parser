<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\GreaterThan;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarNumberOfDoorsGreaterThanFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage\CarQueryParserFactory;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class GreaterThanQueryLanguageOperatorTest extends TestCase
{
    /**
     * @dataProvider queryToBeParsedProvider
     *
     * @param string $query
     */
    public function testOperatorCanBeParsed(string $query): void
    {
        $parser = (new CarQueryParserFactory())->create();

        $result = $parser->parse($query);

        assert($result instanceof CarNumberOfDoorsGreaterThanFilter);
        Assert::assertSame(50, $result->getNumberOfDoors());
    }


    public function queryToBeParsedProvider(): array
    {
        return [
            ['query' => 'numberOfDoors > 50'],
            ['query' => 'numberOfDoors>50'],
            ['query' => 'numberOfDoors >50'],
            ['query' => 'numberOfDoors> 50'],
            ['query' => '  numberOfDoors     >          50    '],
        ];
    }
}
