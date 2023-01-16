<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser;

use BrandEmbassy\QueryLanguageParser\Grammar\QueryLanguageGrammarConfiguration;
use BrandEmbassy\QueryLanguageParser\Grammar\QueryLanguageGrammarFactory;
use BrandEmbassy\QueryLanguageParser\Value\ValueOnlyFilterFactory;
use Ferno\Loco\GrammarException;
use Ferno\Loco\ParseFailureException;

/**
 * @final
 */
class QueryParser
{
    private QueryLanguageGrammarConfiguration $grammarConfiguration;

    private QueryLanguageGrammarFactory $grammarFactory;


    public function __construct(
        QueryLanguageGrammarConfiguration $grammarConfiguration,
        QueryLanguageGrammarFactory $grammarFactory
    ) {
        $this->grammarConfiguration = $grammarConfiguration;
        $this->grammarFactory = $grammarFactory;
    }


    /**
     * @return mixed|null
     *
     * @throws UnableToParseQueryException
     */
    public function parse(string $query, ?ValueOnlyFilterFactory $valueOnlyFilterFactory = null)
    {
        $fields = $this->grammarConfiguration->getFields();
        $operators = $this->grammarConfiguration->getOperators();

        try {
            $grammar = $this->grammarFactory->create($fields, $operators, $valueOnlyFilterFactory);

            return $grammar->parse($query);
        } catch (GrammarException | ParseFailureException $e) {
            throw UnableToParseQueryException::byOtherException($e);
        }
    }
}
