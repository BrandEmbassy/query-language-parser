<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarBrandFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarBrandLikeFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\NotFilter;
use BrandEmbassy\QueryLanguageParser\Operator\EqualTo\QueryLanguageFieldSupportingEqualToOperator;
use BrandEmbassy\QueryLanguageParser\Operator\In\QueryLanguageFieldSupportingInOperator;
use BrandEmbassy\QueryLanguageParser\Operator\Like\QueryLanguageFieldSupportingLikeOperator;
use BrandEmbassy\QueryLanguageParser\Operator\Like\QueryLanguageFieldSupportingLikeSymbolOperator;
use BrandEmbassy\QueryLanguageParser\Operator\NotEqualTo\QueryLanguageFieldSupportingNotEqualToOperator;
use BrandEmbassy\QueryLanguageParser\Operator\NotIn\QueryLanguageFieldSupportingNotInOperator;
use BrandEmbassy\QueryLanguageParser\Operator\NotLike\QueryLanguageFieldSupportingNotLikeOperator;
use BrandEmbassy\QueryLanguageParser\Operator\NotLike\QueryLanguageFieldSupportingNotLikeSymbolOperator;
use BrandEmbassy\QueryLanguageParser\QueryParserContext;
use BrandEmbassy\QueryLanguageParser\Value\MultipleValuesExpressionParserCreator;
use BrandEmbassy\QueryLanguageParser\Value\StringValueParserCreator;
use BrandEmbassy\QueryLanguageParser\Value\TextValueParserCreator;
use Ferno\Loco\GrammarException;
use Ferno\Loco\LazyAltParser;
use Ferno\Loco\MonoParser;
use Ferno\Loco\StringParser;

final class CarBrandQueryLanguageField
    implements QueryLanguageFieldSupportingEqualToOperator,
    QueryLanguageFieldSupportingNotEqualToOperator,
    QueryLanguageFieldSupportingLikeOperator,
    QueryLanguageFieldSupportingNotLikeOperator,
    QueryLanguageFieldSupportingLikeSymbolOperator,
    QueryLanguageFieldSupportingNotLikeSymbolOperator,
    QueryLanguageFieldSupportingInOperator,
    QueryLanguageFieldSupportingNotInOperator
{
    public function getFieldIdentifier(): string
    {
        return 'car.brand';
    }


    public function getFieldNameParserIdentifier(): string
    {
        return 'car.brand.fieldName';
    }


    public function createFieldNameParser(): MonoParser
    {
        return new LazyAltParser(
            [
                TextValueParserCreator::create(),
                new StringParser('brand'),
            ],
        );
    }


    public function getSingleValueParserIdentifier(): string
    {
        return 'car.brand.value';
    }


    /**
     * @return MonoParser
     *
     * @throws GrammarException
     */
    public function createSingleValueParser(): MonoParser
    {
        return new LazyAltParser(
            [
                TextValueParserCreator::create(),
                StringValueParserCreator::create(),
            ],
        );
    }


    public function getMultipleValuesParserIdentifier(): string
    {
        return 'car.brand.value.multiple';
    }


    /**
     * @return MonoParser
     *
     * @throws GrammarException
     */
    public function createMultipleValuesParser(): MonoParser
    {
        return MultipleValuesExpressionParserCreator::create('car.brand.value');
    }


    public function createEqualToOperatorOutput($fieldName, $value, QueryParserContext $context): CarBrandFilter
    {
        return new CarBrandFilter([$value]);
    }


    public function createNotEqualToOperatorOutput($fieldName, $value, QueryParserContext $context): NotFilter
    {
        return new NotFilter(new CarBrandFilter([$value]));
    }


    public function createLikeOperatorOutput($fieldName, $value, QueryParserContext $context): CarBrandLikeFilter
    {
        return new CarBrandLikeFilter($value);
    }


    public function createNotLikeOperatorOutput($fieldName, $value, QueryParserContext $context): NotFilter
    {
        return new NotFilter(new CarBrandLikeFilter($value));
    }


    public function createLikeSymbolOperatorOutput($fieldName, $value, QueryParserContext $context)
    {
        return $this->createLikeOperatorOutput($fieldName, $value, $context);
    }


    public function createNotLikeSymbolOperatorOutput($fieldName, $value, QueryParserContext $context)
    {
        return $this->createNotLikeOperatorOutput($fieldName, $value, $context);
    }


    public function createInOperatorOutput($fieldName, array $values, QueryParserContext $context): CarBrandFilter
    {
        return new CarBrandFilter($values);
    }


    public function createNotInOperatorOutput($fieldName, array $values, QueryParserContext $context): NotFilter
    {
        return new NotFilter(new CarBrandFilter($values));
    }
}
