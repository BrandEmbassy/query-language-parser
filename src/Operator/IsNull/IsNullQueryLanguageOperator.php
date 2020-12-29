<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\IsNull;

use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageField;
use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageOperator;
use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageOperatorParserCreator;
use Ferno\Loco\ConcParser;
use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;

final class IsNullQueryLanguageOperator implements QueryLanguageOperator
{
    private const OPERATOR_IDENTIFIER = 'operator.isNull';


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
        return QueryLanguageOperatorParserCreator::createWordOperatorWithoutRightOperandParser('IS', 'NULL');
    }


    public function isFieldSupported(QueryLanguageField $field): bool
    {
        return $field instanceof QueryLanguageFieldSupportIsNullOperator;
    }


    public function createFieldExpressionParser(QueryLanguageField $field): MonoParser
    {
        assert($field instanceof QueryLanguageFieldSupportIsNullOperator);

        return new ConcParser(
            [
                $field->getFieldNameParserIdentifier(),
                self::OPERATOR_IDENTIFIER,
            ],
            static function ($identifier, $operator) use ($field) {
                return $field->createIsNullOperatorOutput($identifier);
            }
        );
    }
}
