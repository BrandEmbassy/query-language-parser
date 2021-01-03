<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;
use Nette\StaticClass;

final class NumericIdParserCreator
{
    use StaticClass;


    /**
     * @throws GrammarException
     */
    public static function create(): MonoParser
    {
        return PositiveIntegerValueParserCreator::create();
    }
}
