<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;
use Ferno\Loco\RegexParser;
use Nette\StaticClass;

/**
 * @final
 */
class PositiveIntegerValueParserCreator
{
    use StaticClass;

    /**
     * @note:
     * 2000000000 to 2147483647 -> 214748364[0-7]|21474836[0-3]\d|2147483[0-5]\d{2}|214748[0-2]\d{3}|21474[0-7]\d{4}|2147[0-3]\d{5}|214[0-6]\d{6}|21[0-3]\d{7}|20\d{8}
     * 1000000000 to 1999999999 -> 1\d{9}
     * 1 to 999999999 -> [1-9]\d{0,8}
     */
    private const POSITIVE_INTEGER_VALUE_REGEX
        = '#^(214748364[0-7]|21474836[0-3]\d|2147483[0-5]\d{2}|214748[0-2]\d{3}|21474[0-7]\d{4}|2147[0-3]\d{5}|214[0-6]\d{6}|21[0-3]\d{7}|20\d{8}|1\d{9}|[1-9]\d{0,8})#';


    /**
     * @throws GrammarException
     */
    public static function create(): MonoParser
    {
        return new RegexParser(
            self::POSITIVE_INTEGER_VALUE_REGEX,
            static fn($value): int => (int)$value,
        );
    }
}
