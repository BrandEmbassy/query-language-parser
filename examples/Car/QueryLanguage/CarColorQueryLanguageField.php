<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarColorFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarColorLikeFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarHasColorFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\NotFilter;
use BrandEmbassy\QueryLanguageParser\Operator\EqualTo\QueryLanguageFieldSupportingEqualToOperator;
use BrandEmbassy\QueryLanguageParser\Operator\In\QueryLanguageFieldSupportingInOperator;
use BrandEmbassy\QueryLanguageParser\Operator\IsNotNull\QueryLanguageFieldSupportingIsNotNullOperator;
use BrandEmbassy\QueryLanguageParser\Operator\IsNull\QueryLanguageFieldSupportingIsNullOperator;
use BrandEmbassy\QueryLanguageParser\Operator\Like\QueryLanguageFieldSupportingLikeOperator;
use BrandEmbassy\QueryLanguageParser\Operator\Like\QueryLanguageFieldSupportingLikeSymbolOperator;
use BrandEmbassy\QueryLanguageParser\Operator\NotEqualTo\QueryLanguageFieldSupportingNotEqualToOperator;
use BrandEmbassy\QueryLanguageParser\Operator\NotIn\QueryLanguageFieldSupportingNotInOperator;
use BrandEmbassy\QueryLanguageParser\Operator\NotLike\QueryLanguageFieldSupportingNotLikeOperator;
use BrandEmbassy\QueryLanguageParser\Operator\NotLike\QueryLanguageFieldSupportingNotLikeSymbolOperator;
use BrandEmbassy\QueryLanguageParser\QueryParserContext;
use BrandEmbassy\QueryLanguageParser\Value\MultipleValuesExpressionParserCreator;
use BrandEmbassy\QueryLanguageParser\Value\StringValueParserCreator;
use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;
use Ferno\Loco\StringParser;

final class CarColorQueryLanguageField
    implements QueryLanguageFieldSupportingEqualToOperator,
    QueryLanguageFieldSupportingNotEqualToOperator,
    QueryLanguageFieldSupportingLikeOperator,
    QueryLanguageFieldSupportingNotLikeOperator,
    QueryLanguageFieldSupportingLikeSymbolOperator,
    QueryLanguageFieldSupportingNotLikeSymbolOperator,
    QueryLanguageFieldSupportingInOperator,
    QueryLanguageFieldSupportingNotInOperator,
    QueryLanguageFieldSupportingIsNullOperator,
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


    public function createEqualToOperatorOutput($fieldName, $value, QueryParserContext $context): CarColorFilter
    {
        return new CarColorFilter([$value]);
    }


    public function createNotEqualToOperatorOutput($fieldName, $value, QueryParserContext $context): NotFilter
    {
        return new NotFilter(new CarColorFilter([$value]));
    }


    public function createLikeOperatorOutput($fieldName, $value, QueryParserContext $context): CarColorLikeFilter
    {
        return new CarColorLikeFilter($value);
    }


    public function createNotLikeOperatorOutput($fieldName, $value, QueryParserContext $context): NotFilter
    {
        return new NotFilter(new CarColorLikeFilter($value));
    }


    public function createLikeSymbolOperatorOutput($fieldName, $value, QueryParserContext $context)
    {
        return $this->createLikeOperatorOutput($fieldName, $value, $context);
    }


    public function createNotLikeSymbolOperatorOutput($fieldName, $value, QueryParserContext $context)
    {
        return $this->createNotLikeOperatorOutput($fieldName, $value, $context);
    }


    public function createInOperatorOutput($fieldName, array $values, QueryParserContext $context): CarColorFilter
    {
        return new CarColorFilter($values);
    }


    public function createNotInOperatorOutput($fieldName, array $values, QueryParserContext $context): NotFilter
    {
        return new NotFilter(new CarColorFilter($values));
    }


    public function createIsNullOperatorOutput($fieldName, QueryParserContext $context): NotFilter
    {
        return new NotFilter(new CarHasColorFilter());
    }


    public function createIsNotNullOperatorOutput($fieldName, QueryParserContext $context): CarHasColorFilter
    {
        return new CarHasColorFilter();
    }
}
