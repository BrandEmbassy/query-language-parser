<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Grammar;

use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageField;
use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageFieldGrammarFactory;
use BrandEmbassy\QueryLanguageParser\LogicalOperatorOutputFactory;
use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageOperator;
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

final class QueryLanguageGrammarFactory
{
    /**
     * @var QueryLanguageFieldGrammarFactory
     */
    private $fieldGrammarFactory;

    /**
     * @var LogicalOperatorOutputFactory
     */
    private $logicalOperatorOutputFactory;


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
    public function create(array $fields, array $operators): Grammar
    {
        $basicParsers = [
            QueryLanguageGrammarRuleIdentifier::QUERY => new LazyAltParser(
                [
                    new ConcParser(
                        [
                            QueryLanguageGrammarRuleIdentifier::OPTIONAL_WHITESPACE,
                            QueryLanguageGrammarRuleIdentifier::EXPRESSION,
                            QueryLanguageGrammarRuleIdentifier::OPTIONAL_WHITESPACE,
                        ],
                        static function ($whitespace1, $output, $whitespace2) {
                            return $output;
                        }
                    ),
                    new EmptyParser(),
                ]
            ),

            QueryLanguageGrammarRuleIdentifier::EXPRESSION => new LazyAltParser(
                [
                    QueryLanguageGrammarRuleIdentifier::AND_EXPRESSION,
                    QueryLanguageGrammarRuleIdentifier::OR_EXPRESSION,
                    QueryLanguageGrammarRuleIdentifier::SUB_EXPRESSION,
                    QueryLanguageGrammarRuleIdentifier::NOT_SUB_EXPRESSION,
                    QueryLanguageGrammarRuleIdentifier::FIELD_EXPRESSION,
                ]
            ),

            QueryLanguageGrammarRuleIdentifier::SUB_EXPRESSION => new ConcParser(
                [
                    QueryLanguageGrammarRuleIdentifier::OPEN_BRACKET,
                    QueryLanguageGrammarRuleIdentifier::EXPRESSION,
                    QueryLanguageGrammarRuleIdentifier::CLOSE_BRACKET,
                ],
                static function ($openBracket, $subOutput, $closeBracket) {
                    return $subOutput;
                }
            ),

            QueryLanguageGrammarRuleIdentifier::NOT_SUB_EXPRESSION => new ConcParser(
                [
                    QueryLanguageGrammarRuleIdentifier::NOT_OPERATOR,
                    QueryLanguageGrammarRuleIdentifier::OPEN_BRACKET,
                    QueryLanguageGrammarRuleIdentifier::EXPRESSION,
                    QueryLanguageGrammarRuleIdentifier::CLOSE_BRACKET,
                ],
                function ($notOperator, $openBracket, $subOutput, $closeBracket) {
                    return $this->logicalOperatorOutputFactory->createNotOperatorOutput($subOutput);
                }
            ),

            QueryLanguageGrammarRuleIdentifier::AND_EXPRESSION => new ConcParser(
                [
                    new LazyAltParser(
                        [
                            QueryLanguageGrammarRuleIdentifier::FIELD_EXPRESSION,
                            QueryLanguageGrammarRuleIdentifier::SUB_EXPRESSION,
                            QueryLanguageGrammarRuleIdentifier::NOT_SUB_EXPRESSION,
                        ]
                    ),
                    QueryLanguageGrammarRuleIdentifier::AND_OPERATOR,
                    QueryLanguageGrammarRuleIdentifier::EXPRESSION,
                ],
                function (
                    $leftSubOutput,
                    $operator,
                    $rightSubOutput
                ) {
                    return $this->logicalOperatorOutputFactory->createAndOperatorOutput($leftSubOutput, $rightSubOutput);
                }
            ),

            QueryLanguageGrammarRuleIdentifier::OR_EXPRESSION => new ConcParser(
                [
                    new LazyAltParser(
                        [
                            QueryLanguageGrammarRuleIdentifier::FIELD_EXPRESSION,
                            QueryLanguageGrammarRuleIdentifier::SUB_EXPRESSION,
                            QueryLanguageGrammarRuleIdentifier::NOT_SUB_EXPRESSION,
                        ]
                    ),
                    QueryLanguageGrammarRuleIdentifier::OR_OPERATOR,
                    QueryLanguageGrammarRuleIdentifier::EXPRESSION,
                ],
                function (
                    $leftSubOutput,
                    $operator,
                    $rightSubOutput
                ) {
                    return $this->logicalOperatorOutputFactory->createOrOperatorOutput($leftSubOutput, $rightSubOutput);
                }
            ),

            QueryLanguageGrammarRuleIdentifier::FIELD_EXPRESSION => new LazyAltParser(
                array_map(
                    static function (QueryLanguageField $field): string {
                        return $field->getFieldIdentifier();
                    },
                    $fields
                )
            ),

            QueryLanguageGrammarRuleIdentifier::AND_OPERATOR => $this->createLogicalOperatorParser('AND'),
            QueryLanguageGrammarRuleIdentifier::OR_OPERATOR => $this->createLogicalOperatorParser('OR'),
            QueryLanguageGrammarRuleIdentifier::NOT_OPERATOR => $this->createNotOperatorParser('NOT'),

            QueryLanguageGrammarRuleIdentifier::OPEN_BRACKET => new ConcParser(
                [
                    new StringParser('('),
                    QueryLanguageGrammarRuleIdentifier::OPTIONAL_WHITESPACE,
                ]
            ),
            QueryLanguageGrammarRuleIdentifier::CLOSE_BRACKET => new ConcParser(
                [
                    QueryLanguageGrammarRuleIdentifier::OPTIONAL_WHITESPACE,
                    new StringParser(')'),
                ]
            ),

            QueryLanguageGrammarRuleIdentifier::OPTIONAL_WHITESPACE => new RegexParser('#^[ \t]*#'),
            QueryLanguageGrammarRuleIdentifier::REQUIRED_WHITESPACE => new RegexParser('#^[ \t]+#'),
            QueryLanguageGrammarRuleIdentifier::COMMA => new ConcParser(
                [
                    QueryLanguageGrammarRuleIdentifier::OPTIONAL_WHITESPACE,
                    new StringParser(','),
                    QueryLanguageGrammarRuleIdentifier::OPTIONAL_WHITESPACE,
                ]
            ),
        ];

        foreach ($operators as $operator) {
            $basicParsers[$operator->getOperatorIdentifier()] = $operator->createOperatorParser();
        }

        $fieldParsers = [];
        foreach ($fields as $field) {
            $fieldParsers[] = $this->fieldGrammarFactory->createParsers($field, $operators);
        }

        return new Grammar(
            QueryLanguageGrammarRuleIdentifier::QUERY,
            array_merge($basicParsers, ...$fieldParsers)
        );
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
            ]
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
