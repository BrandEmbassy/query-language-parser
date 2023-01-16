<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;
use Ferno\Loco\RegexParser;
use Nette\StaticClass;
use function sprintf;
use function substr;

/**
 * @final
 */
class TextValueParserCreator
{
    use StaticClass;

    //public const TEXT_VALUE_PARSER_TEMPLATE = '/^(["]{1}%s["]{1})|([\']{1}%s[\']{1})$/';
    public const TEXT_VALUE_PARSER_TEMPLATE = '/^(["]{1}%s["]{1})|([\']{1}%s[\']{1})/';


    /**
     * @throws GrammarException
     */
    public static function create(): MonoParser
    {
        return self::createWithCustomPattern('[^"]+', '[^\']+');
    }


    /**
     * @throws GrammarException
     */
    public static function createWithCustomPattern(string $patternForQuotedText, string $patternForSingleQuotedText): MonoParser
    {
        return new RegexParser(
            sprintf(self::TEXT_VALUE_PARSER_TEMPLATE, $patternForQuotedText, $patternForSingleQuotedText),
            static fn($value): string => substr((string)$value, 1, -1),
        );
    }
}
