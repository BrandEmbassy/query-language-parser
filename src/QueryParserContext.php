<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser;

use function serialize;

class QueryParserContext
{
    /**
     * @var array<string, scalar>
     */
    private array $context = [];


    /**
     * @return scalar|null
     */
    public function get(string $key)
    {
        return $this->context[$key] ?? null;
    }


    /**
     * @param scalar $value
     */
    public function set(string $key, $value): void
    {
        $this->context[$key] = $value;
    }


    public function remove(string $key): void
    {
        if (isset($this->context[$key])) {
            unset($this->context[$key]);
        }
    }


    public function toString(): string
    {
        return serialize($this->context);
    }
}
