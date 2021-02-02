<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator;

use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageField;
use Ferno\Loco\MonoParser;

interface QueryLanguageOperator
{
    public function getOperatorIdentifier(): string;


    public function createOperatorParser(): MonoParser;


    public function isFieldSupported(QueryLanguageField $field): bool;


    public function createFieldExpressionParser(QueryLanguageField $field): MonoParser;
}
