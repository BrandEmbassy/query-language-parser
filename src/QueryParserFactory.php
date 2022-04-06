<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser;

use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageField;
use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageFieldGrammarFactory;
use BrandEmbassy\QueryLanguageParser\Grammar\QueryLanguageGrammarConfiguration;
use BrandEmbassy\QueryLanguageParser\Grammar\QueryLanguageGrammarFactory;
use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageOperator;

/**
 * @final
 */
class QueryParserFactory
{
    /**
     * @param array<int, QueryLanguageField> $availableFields
     * @param array<int, QueryLanguageOperator> $availableOperators
     */
    public function create(
        array $availableFields,
        array $availableOperators,
        LogicalOperatorOutputFactory $logicalOperatorOutputFactory
    ): QueryParser {
        $configuration = new QueryLanguageGrammarConfiguration($availableFields, $availableOperators);

        $grammarFactory = new QueryLanguageGrammarFactory(
            new QueryLanguageFieldGrammarFactory(),
            $logicalOperatorOutputFactory,
        );

        return new QueryParser($configuration, $grammarFactory);
    }
}
