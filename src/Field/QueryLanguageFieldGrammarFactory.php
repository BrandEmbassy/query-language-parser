<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Field;

use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageFieldSupportingMultipleValuesOperator;
use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageFieldSupportingSingleValueOperator;
use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageOperator;
use Ferno\Loco\GrammarException;
use Ferno\Loco\LazyAltParser;
use Ferno\Loco\MonoParser;
use function array_keys;
use function array_merge;

final class QueryLanguageFieldGrammarFactory
{
    /**
     * @param QueryLanguageOperator[] $operators
     *
     * @return MonoParser[]
     *
     * @throws GrammarException
     */
    public function createParsers(QueryLanguageField $field, array $operators): array
    {
        $parsers = [$field->getFieldNameParserIdentifier() => $field->createFieldNameParser()];

        $valueParsers = $this->getValueParsers($field);
        $parsers = array_merge($parsers, $valueParsers);

        $operatorParsers = $this->getOperatorParsers($field, $operators);
        $parsers = array_merge($parsers, $operatorParsers);

        $mainFieldParserIdentifier = $field->getFieldIdentifier();
        $mainFieldParser = new LazyAltParser(array_keys($operatorParsers));
        $parsers[$mainFieldParserIdentifier] = $mainFieldParser;

        return $parsers;
    }


    /**
     * @return MonoParser[]
     */
    private function getValueParsers(QueryLanguageField $field): array
    {
        $parsers = [];

        if ($field instanceof QueryLanguageFieldSupportingSingleValueOperator) {
            $parsers[$field->getSingleValueParserIdentifier()] = $field->createSingleValueParser();
        }

        if ($field instanceof QueryLanguageFieldSupportingMultipleValuesOperator) {
            $parsers[$field->getMultipleValuesParserIdentifier()] = $field->createMultipleValuesParser();
        }

        return $parsers;
    }


    /**
     * @param QueryLanguageOperator[] $operators
     *
     * @return MonoParser[]
     */
    private function getOperatorParsers(QueryLanguageField $field, array $operators): array
    {
        $parsers = [];

        foreach ($operators as $operator) {
            if ($operator->isFieldSupported($field)) {
                $parserIdentifier = $field->getFieldIdentifier() . '.' . $operator->getOperatorIdentifier();
                $parsers[$parserIdentifier] = $operator->createFieldExpressionParser($field);
            }
        }

        return $parsers;
    }
}
