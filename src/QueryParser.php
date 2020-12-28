<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser;

use BrandEmbassy\QueryLanguageParser\Grammar\QueryLanguageGrammarConfiguration;
use BrandEmbassy\QueryLanguageParser\Grammar\QueryLanguageGrammarFactory;
use Ferno\Loco\GrammarException;
use Ferno\Loco\ParseFailureException;

final class QueryParser
{
    /**
     * @var QueryLanguageGrammarConfiguration
     */
    private $grammarConfiguration;

    /**
     * @var QueryLanguageGrammarFactory
     */
    private $grammarFactory;


    public function __construct(
        QueryLanguageGrammarConfiguration $grammarConfiguration,
        QueryLanguageGrammarFactory $grammarFactory
    ) {
        $this->grammarConfiguration = $grammarConfiguration;
        $this->grammarFactory = $grammarFactory;
    }


    /**
     * @param string $query
     *
     * @return mixed|null
     *
     * @throws UnableToParseQueryException
     */
    public function parse(string $query)
    {
        $fields = $this->grammarConfiguration->getFields();
        $operators = $this->grammarConfiguration->getOperators();

        try {
            $grammar = $this->grammarFactory->create($fields, $operators);

            return $grammar->parse($query);

        } catch (GrammarException | ParseFailureException $e) {
            throw UnableToParseQueryException::byOtherException($e);
        }
    }
}
