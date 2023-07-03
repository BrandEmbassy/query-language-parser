<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\NotEqualTo;

use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageFieldSupportingSingleValueOperator;
use BrandEmbassy\QueryLanguageParser\QueryParserContext;

interface QueryLanguageFieldSupportingNotEqualToOperator extends QueryLanguageFieldSupportingSingleValueOperator
{
    /**
     * @param mixed $fieldName output of field name parser
     * @param mixed $value     output of single value parser
     *
     * @return mixed
     */
    public function createNotEqualToOperatorOutput($fieldName, $value, QueryParserContext $context);
}
