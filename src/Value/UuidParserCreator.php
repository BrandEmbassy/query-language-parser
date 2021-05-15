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

    private const REGEX = '[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}';


    /**
     * @throws GrammarException
     */
    public static function create(): MonoParser
    {
        return new RegexParser(
            '/^' . self::REGEX . '/',
            static function ($value): UuidInterface {
                return Uuid::fromString($value);
            }
        );
    }
}
