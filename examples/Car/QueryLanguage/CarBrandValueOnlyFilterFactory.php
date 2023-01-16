<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Examples\Car\QueryLanguage;

use BrandEmbassy\QueryLanguageParser\Examples\Car\Filters\CarBrandFilter;
use BrandEmbassy\QueryLanguageParser\Value\ValueOnlyFilterFactory;

/**
 * @final
 */
class CarBrandValueOnlyFilterFactory implements ValueOnlyFilterFactory
{
    /**
     * @inheritDoc
     */
    public function create(string $value)
    {
        return new CarBrandFilter([$value]);
    }
}
