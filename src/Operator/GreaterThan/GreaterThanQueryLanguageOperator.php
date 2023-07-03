<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\GreaterThan;

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
class GreaterThanQueryLanguageOperator implements QueryLanguageOperator
{
    private const OPERATOR_IDENTIFIER = 'operator.greaterThan';


    public function getOperatorIdentifier(): string
    {
        return self::OPERATOR_IDENTIFIER;
    }


    /**
     * @throws GrammarException
     */
    public function createOperatorParser(): MonoParser
    {
        return QueryLanguageOperatorParserCreator::createSignOperatorParser('>');
    }


    public function isFieldSupported(QueryLanguageField $field): bool
    {
        return $field instanceof QueryLanguageFieldSupportingGreaterThanOperator;
    }


    public function createFieldExpressionParser(QueryLanguageField $field, QueryParserContext $context): MonoParser
    {
        assert($field instanceof QueryLanguageFieldSupportingGreaterThanOperator);

        return new ConcParser(
            [
                $field->getFieldNameParserIdentifier(),
                self::OPERATOR_IDENTIFIER,
                $field->getSingleValueParserIdentifier(),
            ],
            static fn($identifier, $operator, $value) => $field->createGreaterThanOperatorOutput($identifier, $value, $context),
        );
    }
}
