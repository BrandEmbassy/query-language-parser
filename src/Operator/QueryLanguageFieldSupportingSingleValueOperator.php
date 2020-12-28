<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator;

use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageField;
use Ferno\Loco\MonoParser;

interface QueryLanguageFieldSupportingSingleValueOperator extends QueryLanguageField
{
    public function getSingleValueParserIdentifier(): string;


    public function createSingleValueParser(): MonoParser;
}
