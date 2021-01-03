<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser\Value;

use BrandEmbassy\QueryLanguageParser\Grammar\QueryLanguageGrammarRuleIdentifier;
use Ferno\Loco\ConcParser;
use Ferno\Loco\GrammarException;
use Ferno\Loco\GreedyMultiParser;
use Ferno\Loco\MonoParser;
use Nette\StaticClass;
use function array_merge;

final class MultipleValuesExpressionParserCreator
{
    use StaticClass;


    /**
     * @param MonoParser|string $singleValueParser
     *
     * @throws GrammarException
     */
    public static function create($singleValueParser): MonoParser
    {
        return new ConcParser(
            [
                QueryLanguageGrammarRuleIdentifier::OPEN_BRACKET,
                $singleValueParser,
                new GreedyMultiParser(
                    new ConcParser(
                        [
                            QueryLanguageGrammarRuleIdentifier::COMMA,
                            $singleValueParser,
                        ],
                        static function ($comma, $value) {
                            return $value;
                        }
                    ),
                    0,
                    null
                ),
                QueryLanguageGrammarRuleIdentifier::CLOSE_BRACKET,
            ],
            static function ($openBracket, $value1, array $otherValues, $closeBracket): array {
                return array_merge([$value1], $otherValues);
            }
        );
    }
}
