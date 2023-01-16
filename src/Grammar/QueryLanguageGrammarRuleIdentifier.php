<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Grammar;

use Nette\StaticClass;

/**
 * @final
 */
class QueryLanguageGrammarRuleIdentifier
{
    use StaticClass;

    public const QUERY = 'query';

    public const EXPRESSION = 'expression';
    public const NOT_SUB_EXPRESSION = 'notSubExpression';
    public const SUB_EXPRESSION = 'subExpression';
    public const FIELD_EXPRESSION = 'field';
    public const VALUE_ONLY_EXPRESSION = 'valueOnly';

    public const OR_EXPRESSION = 'or';
    public const AND_EXPRESSION = 'and';

    public const OR_OPERATOR = 'operator.logical.or';
    public const AND_OPERATOR = 'operator.logical.and';
    public const NOT_OPERATOR = 'operator.logical.not';

    public const OPTIONAL_WHITESPACE = 'whitespace';
    public const REQUIRED_WHITESPACE = 'whitespace.required';
    public const COMMA = 'comma';

    public const OPEN_BRACKET = 'bracket.open';
    public const CLOSE_BRACKET = 'bracket.close';
}
