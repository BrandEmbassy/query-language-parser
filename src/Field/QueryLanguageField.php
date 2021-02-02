<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Field;

use Ferno\Loco\MonoParser;

interface QueryLanguageField
{
    public function getFieldIdentifier(): string;


    public function getFieldNameParserIdentifier(): string;


    public function createFieldNameParser(): MonoParser;
}
