<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

interface ValueOnlyFilterFactory
{
    /**
     * @return mixed Specific filter object
     */
    public function create(string $value);
}
