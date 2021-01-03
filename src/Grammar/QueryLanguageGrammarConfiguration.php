<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Grammar;

use Assert\Assertion;
use BrandEmbassy\QueryLanguageParser\Field\QueryLanguageField;
use BrandEmbassy\QueryLanguageParser\Operator\QueryLanguageOperator;

final class QueryLanguageGrammarConfiguration
{
    /**
     * @var QueryLanguageField[]
     */
    private $fields;

    /**
     * @var QueryLanguageOperator[]
     */
    private $operators;


    /**
     * @param QueryLanguageField[] $fields
     * @param QueryLanguageOperator[] $operators
     */
    public function __construct(array $fields, array $operators)
    {
        Assertion::allIsInstanceOf($fields, QueryLanguageField::class);
        Assertion::allIsInstanceOf($operators, QueryLanguageOperator::class);

        $this->fields = $fields;
        $this->operators = $operators;
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
}
