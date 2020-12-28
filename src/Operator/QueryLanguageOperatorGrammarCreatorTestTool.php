<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator;

use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageField;
use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageFieldGrammarFactory;
use BrandEmbassy\QueryLanguageParser\Grammar\QueryLanguageGrammarFactory;
use Ferno\Loco\Grammar;
use Ferno\Loco\GrammarException;
use Nette\StaticClass;

final class QueryLanguageOperatorGrammarCreatorTestTool
{
    use StaticClass;


    /**
     * @param QueryLanguageOperator $operator
     * @param QueryLanguageField $field
     *
     * @return Grammar
     *
     * @throws GrammarException
     */
    public static function create(QueryLanguageOperator $operator, QueryLanguageField $field): Grammar
    {
        $grammarFactory = new QueryLanguageGrammarFactory(
            new QueryLanguageFieldGrammarFactory(),
            LogicalCaseSearchFilterFactoryCreatorTestTool::create()
        );

        return $grammarFactory->create([$field], [$operator]);
    }
}
