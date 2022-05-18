<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\NotLike;

use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageField;
use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageOperator;
use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageOperatorParserCreator;
use Ferno\Loco\ConcParser;
use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;
use function assert;

/**
 * @final
 */
class NotLikeQueryLanguageOperator implements QueryLanguageOperator
{
    private const OPERATOR_IDENTIFIER = 'operator.notLike';


    public function getOperatorIdentifier(): string
    {
        return self::OPERATOR_IDENTIFIER;
    }


    /**
     * @throws GrammarException
     */
    public function createOperatorParser(): MonoParser
    {
        return QueryLanguageOperatorParserCreator::createWordOperatorParser('NOT LIKE');
    }


    public function isFieldSupported(QueryLanguageField $field): bool
    {
        return $field instanceof QueryLanguageFieldSupportingNotLikeOperator;
    }


    public function createFieldExpressionParser(QueryLanguageField $field): MonoParser
    {
        assert($field instanceof QueryLanguageFieldSupportingNotLikeOperator);

        return new ConcParser(
            [
                $field->getFieldNameParserIdentifier(),
                self::OPERATOR_IDENTIFIER,
                $field->getSingleValueParserIdentifier(),
            ],
            static fn($identifier, $operator, $value) => $field->createNotLikeOperatorOutput($identifier, $value),
        );
    }
}
