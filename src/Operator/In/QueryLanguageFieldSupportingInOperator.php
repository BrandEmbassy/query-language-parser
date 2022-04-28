<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\In;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarFilter;
use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageFieldSupportingMultipleValuesOperator;

interface QueryLanguageFieldSupportingInOperator extends QueryLanguageFieldSupportingMultipleValuesOperator
{
    /**
     * @param mixed $fieldName output of field name parser
     * @param array<int, mixed> $values    output of multiple values parser
     */
    public function createInOperatorOutput($fieldName, array $values): CarFilter;
}
