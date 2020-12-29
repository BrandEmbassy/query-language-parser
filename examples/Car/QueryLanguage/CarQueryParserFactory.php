<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage;

use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageFieldGrammarFactory;
use BrandEmbassy\QueryLanguageParser\Grammar\QueryLanguageGrammarConfiguration;
use BrandEmbassy\QueryLanguageParser\Grammar\QueryLanguageGrammarFactory;
use BrandEmbassy\QueryLanguageParser\Operator\EqualTo\EqualToQueryLanguageOperator;
use BrandEmbassy\QueryLanguageParser\Operator\GreaterThan\GreaterThanQueryLanguageOperator;
use BrandEmbassy\QueryLanguageParser\Operator\GreaterThanOrEqualTo\GreaterThanOrEqualToQueryLanguageOperator;
use BrandEmbassy\QueryLanguageParser\Operator\In\InQueryLanguageOperator;
use BrandEmbassy\QueryLanguageParser\Operator\IsNotNull\IsNotNullQueryLanguageOperator;
use BrandEmbassy\QueryLanguageParser\Operator\IsNull\IsNullQueryLanguageOperator;
use BrandEmbassy\QueryLanguageParser\Operator\LessThan\LessThanQueryLanguageOperator;
use BrandEmbassy\QueryLanguageParser\Operator\LessThanOrEqualTo\LessThanOrEqualToQueryLanguageOperator;
use BrandEmbassy\QueryLanguageParser\Operator\NotEqualTo\NotEqualToQueryLanguageOperator;
use BrandEmbassy\QueryLanguageParser\Operator\NotIn\NotInQueryLanguageOperator;
use BrandEmbassy\QueryLanguageParser\QueryParser;

final class CarQueryParserFactory
{
    public function create(): QueryParser
    {
        $configuration = new QueryLanguageGrammarConfiguration(
            [
                new CarBrandQueryLanguageField(),
                new CarColorQueryLanguageField(),
                new CarNumberOfDoorsQueryLanguageField(),
            ],
            [
                new EqualToQueryLanguageOperator(),
                new NotEqualToQueryLanguageOperator(),
                new InQueryLanguageOperator(),
                new NotInQueryLanguageOperator(),
                new IsNullQueryLanguageOperator(),
                new IsNotNullQueryLanguageOperator(),
                new LessThanQueryLanguageOperator(),
                new LessThanOrEqualToQueryLanguageOperator(),
                new GreaterThanQueryLanguageOperator(),
                new GreaterThanOrEqualToQueryLanguageOperator(),
            ]
        );

        $grammarFactory = new QueryLanguageGrammarFactory(
            new QueryLanguageFieldGrammarFactory(),
            new CarLogicalFiltersFactory()
        );

        return new QueryParser($configuration, $grammarFactory);
    }
}
