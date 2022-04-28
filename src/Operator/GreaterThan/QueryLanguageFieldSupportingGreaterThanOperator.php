<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\GreaterThan;

use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageFieldSupportingSingleValueOperator;

interface QueryLanguageFieldSupportingGreaterThanOperator extends QueryLanguageFieldSupportingSingleValueOperator
{
    /**
     * @param mixed $fieldName output of field name parser
     * @param mixed $value     output of single value parser
     *
     * @return mixed
     */
    public function createGreaterThanOperatorOutput($fieldName, $value);
}
