<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;
use Ferno\Loco\RegexParser;
use Nette\StaticClass;
use function substr;

/**
 * @final
 */
class TextValueParserCreator
{
    use StaticClass;

    public const TEXT_VALUE_PARSER = '/^(["]{1}[^"]+["]{1})|([\']{1}[^\']+[\']{1})/';


    /**
     * @throws GrammarException
     */
    public static function create(): MonoParser
    {
        return new RegexParser(
            self::TEXT_VALUE_PARSER,
            static fn($value): string => substr((string)$value, 1, -1),
        );
    }
}
