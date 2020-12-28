<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;
use Ferno\Loco\RegexParser;
use Nette\StaticClass;

final class NumericValueParserCreator
{
    use StaticClass;

    public const NUMERIC_VALUE_REGEXP = '[\-]?(\d*[.])?(\d+)';


    /**
     * @return MonoParser
     *
     * @throws GrammarException
     */
    public static function create(): MonoParser
    {
        return new RegexParser(
            '#^' . self::NUMERIC_VALUE_REGEXP . '#',
            static function ($value) {
                return strpos($value, '.') === false ? (int)$value : (float)$value;
            }
        );
    }
}
