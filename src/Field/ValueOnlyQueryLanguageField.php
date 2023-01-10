<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Field;

interface ValueOnlyQueryLanguageField extends QueryLanguageField
{
    /**
     * @return mixed
     */
    public function createFilter(string $value);
}
