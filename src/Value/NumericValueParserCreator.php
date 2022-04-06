<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;
use Ferno\Loco\RegexParser;
use Nette\StaticClass;
use function strpos;

/**
 * @final
 */
class NumericValueParserCreator
{
    use StaticClass;

    public const NUMERIC_VALUE_REGEXP = '[\-]?(\d*[.])?(\d+)';


    /**
     * @throws GrammarException
     */
    public static function create(): MonoParser
    {
        return new RegexParser(
            '#^' . self::NUMERIC_VALUE_REGEXP . '#',
            static fn($value) => strpos($value, '.') === false ? (int)$value : (float)$value,
        );
    }
}
