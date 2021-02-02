<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\AndFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\NotFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\OrFilter;
use BrandEmbassy\QueryLanguageParser\LogicalOperatorOutputFactory;
use function assert;

final class CarLogicalFiltersFactory implements LogicalOperatorOutputFactory
{
    public function createNotOperatorOutput($subOutput): NotFilter
    {
        assert($subOutput instanceof CarFilter);

        return new NotFilter($subOutput);
    }


    public function createAndOperatorOutput($leftSubOutput, $rightSubOutput): AndFilter
    {
        assert($leftSubOutput instanceof CarFilter);
        assert($rightSubOutput instanceof CarFilter);

        return new AndFilter($leftSubOutput, $rightSubOutput);
    }


    public function createOrOperatorOutput($leftSubOutput, $rightSubOutput): OrFilter
    {
        assert($leftSubOutput instanceof CarFilter);
        assert($rightSubOutput instanceof CarFilter);

        return new OrFilter($leftSubOutput, $rightSubOutput);
    }
}
