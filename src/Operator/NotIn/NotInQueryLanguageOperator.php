<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\NotIn;

use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageField;
use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageOperator;
use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageOperatorParserCreator;
use Ferno\Loco\ConcParser;
use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;

final class NotInQueryLanguageOperator implements QueryLanguageOperator
{
    private const OPERATOR_IDENTIFIER = 'operator.notIn';


    public function getOperatorIdentifier(): string
    {
        return self::OPERATOR_IDENTIFIER;
    }


    /**
     * @return MonoParser
     *
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


    public function createFieldExpressionParser(QueryLanguageField $field): MonoParser
    {
        assert($field instanceof QueryLanguageFieldSupportingNotInOperator);

        return new ConcParser(
            [
                $field->getFieldNameParserIdentifier(),
                self::OPERATOR_IDENTIFIER,
                $field->getMultipleValuesParserIdentifier(),
            ],
            static function ($identifier, $operator, array $values) use ($field) {
                return $field->createNotInOperatorOutput($identifier, $values);
            }
        );
    }
}
