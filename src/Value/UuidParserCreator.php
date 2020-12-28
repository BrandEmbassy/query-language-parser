<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;
use Ferno\Loco\RegexParser;
use Nette\StaticClass;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UuidParserCreator
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
            '/^[0-9a-f\-]+/',
            static function ($value): UuidInterface {
                return Uuid::fromString($value);
            }
        );
    }
}
