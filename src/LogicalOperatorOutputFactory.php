<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser;

interface LogicalOperatorOutputFactory
{
    public function createNotOperatorOutput($subOutput);


    public function createAndOperatorOutput($leftSubOutput, $rightSubOutput);


    public function createOrOperatorOutput($leftSubOutput, $rightSubOutput);
}
