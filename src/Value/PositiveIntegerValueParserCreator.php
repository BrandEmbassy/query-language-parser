<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;
use Ferno\Loco\RegexParser;
use Nette\StaticClass;

final class PositiveIntegerValueParserCreator
{
    use StaticClass;

    private const POSITIVE_INTEGER_VALUE_REGEX = '#^[1-9][0-9]*#';


    /**
     * @throws GrammarException
     */
    public static function create(): MonoParser
    {
        return new RegexParser(
            self::POSITIVE_INTEGER_VALUE_REGEX,
            static function ($value): int {
                return (int)$value;
            }
        );
    }
}
