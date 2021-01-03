<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Operator;

use BrandEmbassy\QueryLanguageParser\Grammar\QueryLanguageGrammarRuleIdentifier;
use Ferno\Loco\ConcParser;
use Ferno\Loco\GrammarException;
use Ferno\Loco\MonoParser;
use Ferno\Loco\StringParser;
use Nette\StaticClass;

final class QueryLanguageOperatorParserCreator
{
    use StaticClass;


    /**
     * @param string $operator
     *
     * @return MonoParser
     *
     * @throws GrammarException
     */
    public static function createSignOperatorParser(string $operator): MonoParser
    {
        return new ConcParser(
            [
                QueryLanguageGrammarRuleIdentifier::OPTIONAL_WHITESPACE,
                new StringParser($operator),
                QueryLanguageGrammarRuleIdentifier::OPTIONAL_WHITESPACE,
            ]
        );
    }


    /**
     * @param string[] ...$words
     *
     * @return MonoParser
     *
     * @throws GrammarException
     */
    public static function createWordOperatorParser(string ...$words): MonoParser
    {
        $elements = [QueryLanguageGrammarRuleIdentifier::REQUIRED_WHITESPACE];

        foreach ($words as $word) {
            $elements[] = new StringParser($word);
            $elements[] = QueryLanguageGrammarRuleIdentifier::REQUIRED_WHITESPACE;
        }

        return new ConcParser($elements);
    }


    /**
     * @param string[] ...$words
     *
     * @return MonoParser
     *
     * @throws GrammarException
     */
    public static function createWordOperatorWithoutRightOperandParser(string ...$words): MonoParser
    {
        $elements = [];

        foreach ($words as $word) {
            $elements[] = QueryLanguageGrammarRuleIdentifier::REQUIRED_WHITESPACE;
            $elements[] = new StringParser($word);
        }

        return new ConcParser($elements);
    }
}
