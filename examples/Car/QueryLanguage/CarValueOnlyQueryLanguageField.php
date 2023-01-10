<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarBrandFilter;
use BrandEmbassy\QueryLanguageParser\Field\ValueOnlyQueryLanguageField;
use BrandEmbassy\QueryLanguageParser\Value\StringValueParserCreator;
use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;

/**
 * @final
 */
class CarValueOnlyQueryLanguageField implements ValueOnlyQueryLanguageField
{
    public function getFieldIdentifier(): string
    {
        return 'valueOnly';
    }


    public function getFieldNameParserIdentifier(): string
    {
        return 'valueOnly';
    }


    /**
     * @throws GrammarException
     */
    public function createFieldNameParser(): MonoParser
    {
        return StringValueParserCreator::create();
    }


    public function createFilter(string $value)
    {
        return new CarBrandFilter([$value]);
    }
}
