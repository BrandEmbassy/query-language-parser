<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\EqualTo;

use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageField;
use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageOperator;
use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageOperatorParserCreator;
use Ferno\Loco\ConcParser;
use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;

final class EqualToQueryLanguageOperator implements QueryLanguageOperator
{
    private const OPERATOR_IDENTIFIER = 'operator.equalTo';


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
        return QueryLanguageOperatorParserCreator::createSignOperatorParser('=');
    }


    public function isFieldSupported(QueryLanguageField $field): bool
    {
        return $field instanceof QueryLanguageFieldSupportingEqualToOperator;
    }


    public function createFieldExpressionParser(QueryLanguageField $field): MonoParser
    {
        assert($field instanceof QueryLanguageFieldSupportingEqualToOperator);

        return new ConcParser(
            [
                $field->getFieldNameParserIdentifier(),
                self::OPERATOR_IDENTIFIER,
                $field->getSingleValueParserIdentifier(),
            ],
            static function ($identifier, $operator, $value) use ($field) {
                return $field->createEqualToOperatorOutput($identifier, $value);
            }
        );
    }
}
