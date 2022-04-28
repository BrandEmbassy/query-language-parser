<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\Like;

use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageFieldSupportingSingleValueOperator;

interface QueryLanguageFieldSupportingLikeOperator extends QueryLanguageFieldSupportingSingleValueOperator
{
    /**
     * @param mixed $fieldName output of field name parser
     * @param mixed $value     output of single value parser
     *
     * @return mixed
     */
    public function createLikeOperatorOutput($fieldName, $value);
}
