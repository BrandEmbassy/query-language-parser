<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;
use Ferno\Loco\RegexParser;
use Nette\StaticClass;

final class PositiveIntegerValueParserCreator
{
    use StaticClass;


    /**
     * @return MonoParser
     *
     * @throws GrammarException
     */
    public static function create(): MonoParser
    {
        return new RegexParser(
            '#^[1-9][0-9]*#',
            static function ($value) {
                return strpos($value, '.') === false ? (int)$value : (float)$value;
            }
        );
    }
}
