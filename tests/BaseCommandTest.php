<?php

namespace pxgamer\ArionumCLI;

use PHPUnit\Framework\TestCase;
use pxgamer\ArionumCLI\Output\Factory;

class BaseCommandTest extends TestCase
{
    /** @test */
    public function itCanConstructAFactory(): void
    {
        $command = new class extends BaseCommand
        {
        };

        $this->assertAttributeEquals(null, 'wallet', $command);
        $this->assertAttributeEquals(null, 'questionHelper', $command);
        $this->assertAttributeEquals(null, 'outputFactory', $command);

        $factory = new Factory();
        $command = new class($factory) extends BaseCommand
        {
        };

        $this->assertAttributeInstanceOf(Factory::class, 'outputFactory', $command);
    }
}
