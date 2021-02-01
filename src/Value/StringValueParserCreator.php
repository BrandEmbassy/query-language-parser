<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;
use Ferno\Loco\RegexParser;
use Nette\StaticClass;

final class StringValueParserCreator
{
    use StaticClass;

    private const STRING_VALUE_PARSER = '/^[\w\-]+/';


    /**
     * @throws GrammarException
     */
    public static function create(): MonoParser
    {
        return new RegexParser(
            self::STRING_VALUE_PARSER,
            static function ($value): string {
                return (string)$value;
            }
        );
    }
}
