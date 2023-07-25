<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;
use Ferno\Loco\RegexParser;
use Nette\StaticClass;
use function trim;

/**
 * @final
 */
class PositiveIntegerValueParserCreator
{
    use StaticClass;

    private const POSITIVE_INTEGER_VALUE_REGEX = '#^\s*([1-9]\d{0,8}|1\d{9}|20\d{8}|21[0-3]\d{7}|214[0-6]\d{6}|2147[0-3]\d{5}|21474[0-7]\d{4}|214748[0-2]\d{3}|2147483[0-5]\d{2}|21474836[0-3]\d|214748364[0-7])\s*$#';


    /**
     * @throws GrammarException
     */
    public static function create(): MonoParser
    {
        return new RegexParser(
            self::POSITIVE_INTEGER_VALUE_REGEX,
            static fn($value): int => (int)trim($value),
        );
    }
}
