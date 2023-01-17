<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\GreaterThan;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarNumberOfDoorsGreaterThanFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage\CarQueryParserFactory;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Throwable;
use function assert;

/**
 * @final
 */
class GreaterThanQueryLanguageOperatorTest extends TestCase
{
    private const DO_NOT_USE_VALUE_ONLY_FILTER = false;


    /**
     * @dataProvider queryToBeParsedProvider
     *
     * @throws Throwable
     */
    public function testOperatorCanBeParsed(string $query): void
    {
        $parser = (new CarQueryParserFactory())->create(self::DO_NOT_USE_VALUE_ONLY_FILTER);

        $result = $parser->parse($query);

        assert($result instanceof CarNumberOfDoorsGreaterThanFilter);
        Assert::assertSame(50, $result->getNumberOfDoors());
    }


    /**
     * @return string[][]
     */
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
