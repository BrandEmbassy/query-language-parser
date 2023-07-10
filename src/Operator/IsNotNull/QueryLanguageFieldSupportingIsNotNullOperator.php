<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator\IsNotNull;

use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageField;
use BrandEmbassy\QueryLanguageParser\QueryParserContext;

interface QueryLanguageFieldSupportingIsNotNullOperator extends QueryLanguageField
{
    /**
     * @param mixed $fieldName output of field name parser
     *
     * @return mixed
     */
    public function createIsNotNullOperatorOutput($fieldName, QueryParserContext $context);
}
