<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Grammar;

use Assert\Assertion;
use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageField;
use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageOperator;
use BrandEmbassy\QueryLanguageParser\Value\ValueOnlyFilterFactory;

/**
 * @final
 */
class QueryLanguageGrammarConfiguration
{
    /**
     * @var QueryLanguageField[]
     */
    private array $fields;

    /**
     * @var QueryLanguageOperator[]
     */
    private array $operators;

    private ?ValueOnlyFilterFactory $valueOnlyFilterFactory;


    /**
     * @param QueryLanguageField[] $fields
     * @param QueryLanguageOperator[] $operators
     */
    public function __construct(
        array $fields,
        array $operators,
        ?ValueOnlyFilterFactory $valueOnlyFilterFactory = null
    ) {
        Assertion::allIsInstanceOf($fields, QueryLanguageField::class);
        Assertion::allIsInstanceOf($operators, QueryLanguageOperator::class);

        $this->fields = $fields;
        $this->operators = $operators;
        $this->valueOnlyFilterFactory = $valueOnlyFilterFactory;
    }


    /**
     * @return QueryLanguageField[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }


    /**
     * @return QueryLanguageOperator[]
     */
    public function getOperators(): array
    {
        return $this->operators;
    }


    /**
     * @return ValueOnlyFilterFactory|null
     */
    public function getValueOnlyFilterFactory(): ?ValueOnlyFilterFactory
    {
        return $this->valueOnlyFilterFactory;
    }
}
