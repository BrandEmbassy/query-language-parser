<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use BrandEmbassy\QueryLanguageParser\Field\ValueOnlyQueryLanguageField;
use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;
use Ferno\Loco\RegexParser;
use Nette\StaticClass;

/**
 * @final
 */
class ValueOnlyParserCreator
{
    use StaticClass;

    private const STRING_VALUE_PARSER = '/^[^\s,()=<>~]+$/';


    /**
     * @throws GrammarException
     */
    public static function create(ValueOnlyQueryLanguageField $field): MonoParser
    {
        return new RegexParser(
            self::STRING_VALUE_PARSER,
            static fn(string $value) => $field->createFilter($value),
        );
    }
}
