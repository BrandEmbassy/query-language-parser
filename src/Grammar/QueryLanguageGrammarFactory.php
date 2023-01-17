<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Grammar;

use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageField;
use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageFieldGrammarFactory;
use BrandEmbassy\QueryLanguageParser\LogicalOperatorOutputFactory;
use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageOperator;
use BrandEmbassy\QueryLanguageParser\Value\ValueOnlyFilterFactory;
use BrandEmbassy\QueryLanguageParser\Value\ValueOnlyParserCreator;
use Ferno\Loco\ConcParser;
use Ferno\Loco\EmptyParser;
use Ferno\Loco\Grammar;
use Ferno\Loco\GrammarException;
use Ferno\Loco\LazyAltParser;
use Ferno\Loco\MonoParser;
use Ferno\Loco\RegexParser;
use Ferno\Loco\StringParser;
use function array_map;
use function array_merge;

/**
 * @final
 */
class QueryLanguageGrammarFactory
{
    private QueryLanguageFieldGrammarFactory $fieldGrammarFactory;

    private LogicalOperatorOutputFactory $logicalOperatorOutputFactory;


    public function __construct(
        QueryLanguageFieldGrammarFactory $fieldGrammarFactory,
        LogicalOperatorOutputFactory $logicalOperatorOutputFactory
    ) {
        $this->fieldGrammarFactory = $fieldGrammarFactory;
        $this->logicalOperatorOutputFactory = $logicalOperatorOutputFactory;
    }


    /**
     * @param QueryLanguageField[] $fields
     * @param QueryLanguageOperator[] $operators
     *
     * @throws GrammarException
     */
    public function create(
        array $fields,
        array $operators,
        ?ValueOnlyFilterFactory $valueOnlyFilterFactory = null
    ): Grammar {
        $queryLazyAltParserInternals = [
            new ConcParser(
                [
                    QueryLanguageGrammarRuleIdentifier::OPTIONAL_WHITESPACE,
                    QueryLanguageGrammarRuleIdentifier::EXPRESSION,
                    QueryLanguageGrammarRuleIdentifier::OPTIONAL_WHITESPACE,
                ],
                static fn($whitespace1, $output, $whitespace2) => $output,
            ),
            new EmptyParser(),
        ];

        $basicParsers = [
            QueryLanguageGrammarRuleIdentifier::QUERY => new LazyAltParser($queryLazyAltParserInternals),

            QueryLanguageGrammarRuleIdentifier::EXPRESSION => new LazyAltParser(
                $this->getAllowedExpressionIdentifiers($valueOnlyFilterFactory),
            ),

            QueryLanguageGrammarRuleIdentifier::SUB_EXPRESSION => new ConcParser(
                [
                    QueryLanguageGrammarRuleIdentifier::OPEN_BRACKET,
                    QueryLanguageGrammarRuleIdentifier::EXPRESSION,
                    QueryLanguageGrammarRuleIdentifier::CLOSE_BRACKET,
                ],
                static fn($openBracket, $subOutput, $closeBracket) => $subOutput,
            ),

            QueryLanguageGrammarRuleIdentifier::NOT_SUB_EXPRESSION => new ConcParser(
                [
                    QueryLanguageGrammarRuleIdentifier::NOT_OPERATOR,
                    QueryLanguageGrammarRuleIdentifier::OPEN_BRACKET,
                    QueryLanguageGrammarRuleIdentifier::EXPRESSION,
                    QueryLanguageGrammarRuleIdentifier::CLOSE_BRACKET,
                ],
                fn(
                    $notOperator,
                    $openBracket,
                    $subOutput,
                    $closeBracket
                ) => $this->logicalOperatorOutputFactory->createNotOperatorOutput($subOutput),
            ),

            QueryLanguageGrammarRuleIdentifier::AND_EXPRESSION => new ConcParser(
                [
                    new LazyAltParser($this->getAllowedChildExpressionIdentifiers($valueOnlyFilterFactory)),
                    QueryLanguageGrammarRuleIdentifier::AND_OPERATOR,
                    QueryLanguageGrammarRuleIdentifier::EXPRESSION,
                ],
                fn(
                    $leftSubOutput,
                    $operator,
                    $rightSubOutput
                ) => $this->logicalOperatorOutputFactory->createAndOperatorOutput($leftSubOutput, $rightSubOutput),
            ),

            QueryLanguageGrammarRuleIdentifier::OR_EXPRESSION => new ConcParser(
                [
                    new LazyAltParser($this->getAllowedChildExpressionIdentifiers($valueOnlyFilterFactory)),
                    QueryLanguageGrammarRuleIdentifier::OR_OPERATOR,
                    QueryLanguageGrammarRuleIdentifier::EXPRESSION,
                ],
                fn(
                    $leftSubOutput,
                    $operator,
                    $rightSubOutput
                ) => $this->logicalOperatorOutputFactory->createOrOperatorOutput($leftSubOutput, $rightSubOutput),
            ),

            QueryLanguageGrammarRuleIdentifier::FIELD_EXPRESSION => new LazyAltParser(
                array_map(
                    static fn(QueryLanguageField $field): string => $field->getFieldIdentifier(),
                    $fields,
                ),
            ),

            QueryLanguageGrammarRuleIdentifier::AND_OPERATOR => $this->createLogicalOperatorParser('AND'),
            QueryLanguageGrammarRuleIdentifier::OR_OPERATOR => $this->createLogicalOperatorParser('OR'),
            QueryLanguageGrammarRuleIdentifier::NOT_OPERATOR => $this->createNotOperatorParser('NOT'),

            QueryLanguageGrammarRuleIdentifier::OPEN_BRACKET => new ConcParser(
                [
                    new StringParser('('),
                    QueryLanguageGrammarRuleIdentifier::OPTIONAL_WHITESPACE,
                ],
            ),
            QueryLanguageGrammarRuleIdentifier::CLOSE_BRACKET => new ConcParser(
                [
                    QueryLanguageGrammarRuleIdentifier::OPTIONAL_WHITESPACE,
                    new StringParser(')'),
                ],
            ),

            QueryLanguageGrammarRuleIdentifier::OPTIONAL_WHITESPACE => new RegexParser('#^[ \t]*#'),
            QueryLanguageGrammarRuleIdentifier::REQUIRED_WHITESPACE => new RegexParser('#^[ \t]+#'),
            QueryLanguageGrammarRuleIdentifier::COMMA => new ConcParser(
                [
                    QueryLanguageGrammarRuleIdentifier::OPTIONAL_WHITESPACE,
                    new StringParser(','),
                    QueryLanguageGrammarRuleIdentifier::OPTIONAL_WHITESPACE,
                ],
            ),
        ];

        if ($valueOnlyFilterFactory !== null) {
            $basicParsers[QueryLanguageGrammarRuleIdentifier::VALUE_ONLY_EXPRESSION] = ValueOnlyParserCreator::create(
                static fn(string $value) => $valueOnlyFilterFactory->create($value),
            );
        }

        foreach ($operators as $operator) {
            $basicParsers[$operator->getOperatorIdentifier()] = $operator->createOperatorParser();
        }

        $fieldParsers = [];
        foreach ($fields as $field) {
            $fieldParsers[] = $this->fieldGrammarFactory->createParsers($field, $operators);
        }

        return new Grammar(
            QueryLanguageGrammarRuleIdentifier::QUERY,
            array_merge($basicParsers, ...$fieldParsers),
        );
    }


    /**
     * @return array<string>
     */
    private function getAllowedExpressionIdentifiers(?ValueOnlyFilterFactory $valueOnlyFilterFactory = null): array
    {
        $allowedExpressions = [
            QueryLanguageGrammarRuleIdentifier::AND_EXPRESSION,
            QueryLanguageGrammarRuleIdentifier::OR_EXPRESSION,
            QueryLanguageGrammarRuleIdentifier::SUB_EXPRESSION,
            QueryLanguageGrammarRuleIdentifier::NOT_SUB_EXPRESSION,
            QueryLanguageGrammarRuleIdentifier::FIELD_EXPRESSION,
        ];
        if ($valueOnlyFilterFactory !== null) {
            $allowedExpressions[] = QueryLanguageGrammarRuleIdentifier::VALUE_ONLY_EXPRESSION;
        }

        return $allowedExpressions;
    }


    /**
     * @return array<string>
     */
    private function getAllowedChildExpressionIdentifiers(?ValueOnlyFilterFactory $valueOnlyFilterFactory = null): array
    {
        $childExpressionIdentifiers = [
            QueryLanguageGrammarRuleIdentifier::FIELD_EXPRESSION,
            QueryLanguageGrammarRuleIdentifier::SUB_EXPRESSION,
            QueryLanguageGrammarRuleIdentifier::NOT_SUB_EXPRESSION,
        ];
        if ($valueOnlyFilterFactory !== null) {
            $childExpressionIdentifiers[] = QueryLanguageGrammarRuleIdentifier::VALUE_ONLY_EXPRESSION;
        }

        return $childExpressionIdentifiers;
    }


    /**
     * @throws GrammarException
     */
    private function createNotOperatorParser(string $operator): MonoParser
    {
        return new ConcParser(
            [
                QueryLanguageGrammarRuleIdentifier::OPTIONAL_WHITESPACE,
                new StringParser($operator),
                QueryLanguageGrammarRuleIdentifier::OPTIONAL_WHITESPACE,
            ],
        );
    }


    /**
     * @param string[] ...$words
     *
     * @throws GrammarException
     */
    private function createLogicalOperatorParser(string ...$words): MonoParser
    {
        $elements = [QueryLanguageGrammarRuleIdentifier::REQUIRED_WHITESPACE];

        foreach ($words as $word) {
            $elements[] = new StringParser($word);
            $elements[] = QueryLanguageGrammarRuleIdentifier::REQUIRED_WHITESPACE;
        }

        return new ConcParser($elements);
    }
}
