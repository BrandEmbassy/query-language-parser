<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\LessThanOrEqualTo;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarNumberOfDoorsLessThanOrEqualFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage\CarQueryParserFactory;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use function assert;

/**
 * @final
 */
class LessThanOrEqualToQueryLanguageOperatorTest extends TestCase
{
    /**
     * @dataProvider queryToBeParsedProvider
     */
    public function testOperatorCanBeParsed(string $query): void
    {
        $parser = (new CarQueryParserFactory())->create();

        $result = $parser->parse($query);

        assert($result instanceof CarNumberOfDoorsLessThanOrEqualFilter);
        Assert::assertSame(50, $result->getNumberOfDoors());
    }


    /**
     * @return string[][]
     */
    public function queryToBeParsedProvider(): array
    {
        return [
            ['query' => 'numberOfDoors <= 50'],
            ['query' => 'numberOfDoors<=50'],
            ['query' => 'numberOfDoors <=50'],
            ['query' => 'numberOfDoors<= 50'],
            ['query' => '  numberOfDoors     <=          50    '],
        ];
    }
}
