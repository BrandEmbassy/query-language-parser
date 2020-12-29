<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\In;

use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageField;
use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageOperator;
use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageOperatorParserCreator;
use Ferno\Loco\ConcParser;
use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;

final class InQueryLanguageOperator implements QueryLanguageOperator
{
    private const OPERATOR_IDENTIFIER = 'operator.in';


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
        return QueryLanguageOperatorParserCreator::createWordOperatorParser('IN');
    }


    public function isFieldSupported(QueryLanguageField $field): bool
    {
        return $field instanceof QueryLanguageFieldSupportingInOperator;
    }


    public function createFieldExpressionParser(QueryLanguageField $field): MonoParser
    {
        assert($field instanceof QueryLanguageFieldSupportingInOperator);

        return new ConcParser(
            [
                $field->getFieldNameParserIdentifier(),
                self::OPERATOR_IDENTIFIER,
                $field->getMultipleValuesParserIdentifier(),
            ],
            static function ($identifier, $operator, array $values) use ($field) {
                return $field->createInOperatorOutput($identifier, $values);
            }
        );
    }
}
