<?php declare(strict_types = 1);

namespace BrandEmbassy\QueryLanguageParser;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class WorldTest extends TestCase
{
    public function testSayHello(): void
    {
        $result = (new World())->sayHello();

        Assert::assertSame('Hello world', $result);
    }
}
