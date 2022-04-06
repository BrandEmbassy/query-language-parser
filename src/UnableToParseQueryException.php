<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser;

use Exception;
use Throwable;

/**
 * @final
 */
class UnableToParseQueryException extends Exception
{
    public static function byOtherException(Throwable $e): self
    {
        return new self($e->getMessage(), $e->getCode(), $e);
    }
}
