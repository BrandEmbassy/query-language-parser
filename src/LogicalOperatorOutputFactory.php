<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser;

interface LogicalOperatorOutputFactory
{
    /**
     * @param mixed $subOutput
     *
     * @return mixed
     */
    public function createNotOperatorOutput($subOutput);


    /**
     * @param mixed $leftSubOutput
     * @param mixed $rightSubOutput
     *
     * @return mixed
     */
    public function createAndOperatorOutput($leftSubOutput, $rightSubOutput);


    /**
     * @param mixed $leftSubOutput
     * @param mixed $rightSubOutput
     *
     * @return mixed
     */
    public function createOrOperatorOutput($leftSubOutput, $rightSubOutput);
}
