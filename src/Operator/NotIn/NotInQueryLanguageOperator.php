<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\NotIn;

use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageField;
use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageOperator;
use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageOperatorParserCreator;
use BrandEmbassy\QueryLanguageParser\QueryParserContext;
use Ferno\Loco\ConcParser;
use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;
use function assert;

/**
 * @final
 */
class NotInQueryLanguageOperator implements QueryLanguageOperator
{
    private const OPERATOR_IDENTIFIER = 'operator.notIn';


    public function getOperatorIdentifier(): string
    {
        return self::OPERATOR_IDENTIFIER;
    }


    /**
     * @throws GrammarException
     */
    public function createOperatorParser(): MonoParser
    {
        return QueryLanguageOperatorParserCreator::createWordOperatorParser('NOT', 'IN');
    }


    public function isFieldSupported(QueryLanguageField $field): bool
    {
        return $field instanceof QueryLanguageFieldSupportingNotInOperator;
    }


    public function createFieldExpressionParser(QueryLanguageField $field, QueryParserContext $context): MonoParser
    {
        assert($field instanceof QueryLanguageFieldSupportingNotInOperator);

        return new ConcParser(
            [
                $field->getFieldNameParserIdentifier(),
                self::OPERATOR_IDENTIFIER,
                $field->getMultipleValuesParserIdentifier(),
            ],
            static fn($identifier, $operator, array $values) => $field->createNotInOperatorOutput($identifier, $values, $context),
        );
    }
}
