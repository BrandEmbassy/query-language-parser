<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\IsNull;

use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageField;

interface QueryLanguageFieldSupportIsNullOperator extends QueryLanguageField
{
    /**
     * @param mixed $fieldName output of field name parser
     *
     * @return mixed
     */
    public function createIsNullOperatorOutput($fieldName);
}
