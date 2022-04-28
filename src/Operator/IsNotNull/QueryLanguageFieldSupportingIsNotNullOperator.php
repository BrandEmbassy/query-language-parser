<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\IsNotNull;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarFilter;
use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageField;

interface QueryLanguageFieldSupportingIsNotNullOperator extends QueryLanguageField
{
    /**
     * @param mixed $fieldName output of field name parser
     */
    public function createIsNotNullOperatorOutput($fieldName): CarFilter;
}
