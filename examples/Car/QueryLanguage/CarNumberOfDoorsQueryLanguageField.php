<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarNumberOfDoorsFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarNumberOfDoorsGreaterThanFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarNumberOfDoorsGreaterThanOrEqualFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarNumberOfDoorsLessThanFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarNumberOfDoorsLessThanOrEqualFilter;
use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\NotFilter;
use BrandEmbassy\QueryLanguageParser\Operator\EqualTo\QueryLanguageFieldSupportingEqualToOperator;
use BrandEmbassy\QueryLanguageParser\Operator\GreaterThan\QueryLanguageFieldSupportingGreaterThanOperator;
use BrandEmbassy\QueryLanguageParser\Operator\GreaterThanOrEqualTo\QueryLanguageFieldSupportingGreaterThanOrEqualToOperator;
use BrandEmbassy\QueryLanguageParser\Operator\In\QueryLanguageFieldSupportingInOperator;
use BrandEmbassy\QueryLanguageParser\Operator\LessThan\QueryLanguageFieldSupportingLessThanOperator;
use BrandEmbassy\QueryLanguageParser\Operator\LessThanOrEqualTo\QueryLanguageFieldSupportingLessThanOrEqualToOperator;
use BrandEmbassy\QueryLanguageParser\Operator\NotEqualTo\QueryLanguageFieldSupportingNotEqualToOperator;
use BrandEmbassy\QueryLanguageParser\Operator\NotIn\QueryLanguageFieldSupportingNotInOperator;
use BrandEmbassy\QueryLanguageParser\QueryParserContext;
use BrandEmbassy\QueryLanguageParser\Value\MultipleValuesExpressionParserCreator;
use BrandEmbassy\QueryLanguageParser\Value\PositiveIntegerValueParserCreator;
use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;
use Ferno\Loco\StringParser;

final class CarNumberOfDoorsQueryLanguageField
    implements QueryLanguageFieldSupportingEqualToOperator,
    QueryLanguageFieldSupportingNotEqualToOperator,
    QueryLanguageFieldSupportingInOperator,
    QueryLanguageFieldSupportingNotInOperator,
    QueryLanguageFieldSupportingLessThanOperator,
    QueryLanguageFieldSupportingLessThanOrEqualToOperator,
    QueryLanguageFieldSupportingGreaterThanOperator,
    QueryLanguageFieldSupportingGreaterThanOrEqualToOperator
{
    public function getFieldIdentifier(): string
    {
        return 'car.numberOfDoors';
    }


    public function getFieldNameParserIdentifier(): string
    {
        return 'car.numberOfDoors.fieldName';
    }


    public function createFieldNameParser(): MonoParser
    {
        return new StringParser('numberOfDoors');
    }


    public function getSingleValueParserIdentifier(): string
    {
        return 'car.numberOfDoors.value';
    }


    /**
     * @return MonoParser
     *
     * @throws GrammarException
     */
    public function createSingleValueParser(): MonoParser
    {
        return PositiveIntegerValueParserCreator::create();
    }


    public function getMultipleValuesParserIdentifier(): string
    {
        return 'car.numberOfDoors.value.multiple';
    }


    /**
     * @return MonoParser
     *
     * @throws GrammarException
     */
    public function createMultipleValuesParser(): MonoParser
    {
        return MultipleValuesExpressionParserCreator::create('car.numberOfDoors.value');
    }


    public function createEqualToOperatorOutput($fieldName, $value, QueryParserContext $context): CarNumberOfDoorsFilter
    {
        $dynamicModifier = $context->get('number_of_doors_modifier') ?? 0;

        return new CarNumberOfDoorsFilter([$value + $dynamicModifier]);
    }


    public function createNotEqualToOperatorOutput($fieldName, $value, QueryParserContext $context): NotFilter
    {
        return new NotFilter(new CarNumberOfDoorsFilter([$value]));
    }


    public function createInOperatorOutput($fieldName, array $values, QueryParserContext $context): CarNumberOfDoorsFilter
    {
        return new CarNumberOfDoorsFilter($values);
    }


    public function createNotInOperatorOutput($fieldName, array $values, QueryParserContext $context): NotFilter
    {
        return new NotFilter(new CarNumberOfDoorsFilter($values));
    }


    /**
     * @inheritDoc
     */
    public function createGreaterThanOperatorOutput(
        $fieldName,
        $value,
        QueryParserContext $context
    ): CarNumberOfDoorsGreaterThanFilter {
        return new CarNumberOfDoorsGreaterThanFilter($value);
    }


    /**
     * @inheritDoc
     */
    public function createGreaterThanOrEqualToOperatorOutput(
        $fieldName,
        $value,
        QueryParserContext $context
    ): CarNumberOfDoorsGreaterThanOrEqualFilter {
        return new CarNumberOfDoorsGreaterThanOrEqualFilter($value);
    }


    /**
     * @inheritDoc
     */
    public function createLessThanOperatorOutput(
        $fieldName,
        $value,
        QueryParserContext $context
    ): CarNumberOfDoorsLessThanFilter {
        return new CarNumberOfDoorsLessThanFilter($value);
    }


    /**
     * @inheritDoc
     */
    public function createLessThanOrEqualToOperatorOutput(
        $fieldName,
        $value,
        QueryParserContext $context
    ): CarNumberOfDoorsLessThanOrEqualFilter {
        return new CarNumberOfDoorsLessThanOrEqualFilter($value);
    }
}
