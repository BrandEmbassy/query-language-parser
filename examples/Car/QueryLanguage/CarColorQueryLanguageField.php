<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarColorFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarHasColorFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\NotFilter;
use BrandEmbassy\QueryLanguageParser\Operator\EqualTo\QueryLanguageFieldSupportingEqualToOperator;
use BrandEmbassy\QueryLanguageParser\Operator\In\QueryLanguageFieldSupportingInOperator;
use BrandEmbassy\QueryLanguageParser\Operator\IsNotNull\QueryLanguageFieldSupportingIsNotNullOperator;
use BrandEmbassy\QueryLanguageParser\Operator\IsNull\QueryLanguageFieldSupportIsNullOperator;
use BrandEmbassy\QueryLanguageParser\Operator\NotEqualTo\QueryLanguageFieldSupportingNotEqualToOperator;
use BrandEmbassy\QueryLanguageParser\Operator\NotIn\QueryLanguageFieldSupportingNotInOperator;
use BrandEmbassy\QueryLanguageParser\Value\MultipleValuesExpressionParserCreator;
use BrandEmbassy\QueryLanguageParser\Value\StringValueParserCreator;
use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;
use Ferno\Loco\StringParser;

final class CarColorQueryLanguageField
    implements QueryLanguageFieldSupportingEqualToOperator,
    QueryLanguageFieldSupportingNotEqualToOperator,
    QueryLanguageFieldSupportingInOperator,
    QueryLanguageFieldSupportingNotInOperator,
    QueryLanguageFieldSupportIsNullOperator,
    QueryLanguageFieldSupportingIsNotNullOperator
{
    public function getFieldIdentifier(): string
    {
        return 'car.color';
    }


    public function getFieldNameParserIdentifier(): string
    {
        return 'car.color.fieldName';
    }


    public function createFieldNameParser(): MonoParser
    {
        return new StringParser('color');
    }


    public function getSingleValueParserIdentifier(): string
    {
        return 'car.color.value';
    }


    /**
     * @return MonoParser
     *
     * @throws GrammarException
     */
    public function createSingleValueParser(): MonoParser
    {
        return StringValueParserCreator::create();
    }


    public function getMultipleValuesParserIdentifier(): string
    {
        return 'car.color.value.multiple';
    }


    /**
     * @return MonoParser
     *
     * @throws GrammarException
     */
    public function createMultipleValuesParser(): MonoParser
    {
        return MultipleValuesExpressionParserCreator::create('car.color.value');
    }


    public function createEqualToOperatorOutput($fieldName, $value)
    {
        return new CarColorFilter([$value]);
    }


    public function createNotEqualToOperatorOutput($fieldName, $value)
    {
        return new NotFilter(new CarColorFilter([$value]));
    }


    public function createInOperatorOutput($fieldName, array $values)
    {
        return new CarColorFilter($values);
    }


    public function createNotInOperatorOutput($fieldName, array $values)
    {
        return new NotFilter(new CarColorFilter($values));
    }


    public function createIsNullOperatorOutput($fieldName)
    {
        return new CarHasColorFilter();
    }


    public function createIsNotNullOperatorOutput($fieldName)
    {
        return new NotFilter(new CarHasColorFilter());
    }
}
