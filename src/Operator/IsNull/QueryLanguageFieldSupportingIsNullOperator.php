<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\IsNull;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarFilter;
use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageField;

interface QueryLanguageFieldSupportingIsNullOperator extends QueryLanguageField
{
    /**
     * @param mixed $fieldName output of field name parser
     */
    public function createIsNullOperatorOutput($fieldName): CarFilter;
}
