<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use Ferno\Loco\GrammarException;
use Ferno\Loco\LazyAltParser;
use Ferno\Loco\MonoParser;
use Nette\StaticClass;

/**
 * @final
 */
class ValueOnlyParserCreator
{
    use StaticClass;


    /**
     * @throws GrammarException
     */
    public static function create(?callable $callback = null): MonoParser
    {
        return new LazyAltParser(
            [
                TextValueParserCreator::createWithCustomPattern(
                    '[^,()=<>~"]+',
                    '[^,()=<>~\']+'
                ),
                StringValueParserCreator::create(),
            ],
            $callback,
        );
    }
}
