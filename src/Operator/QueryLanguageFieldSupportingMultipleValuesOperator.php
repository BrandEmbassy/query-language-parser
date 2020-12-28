<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator;

use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageField;
use Ferno\Loco\MonoParser;

interface QueryLanguageFieldSupportingMultipleValuesOperator extends QueryLanguageField
{
    public function getMultipleValuesParserIdentifier(): string;


    public function createMultipleValuesParser(): MonoParser;
}
