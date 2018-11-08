<?php

namespace pxgamer\ArionumCLI\Console;

use PHPUnit\Framework\TestCase;
use pxgamer\ArionumCLI\Console\Output\Factory;

class BaseCommandTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function itCanConstructAFactory(): void
    {
        $command = new class extends BaseCommand {
        };

        $this->assertAttributeEquals(null, 'wallet', $command);
        $this->assertAttributeEquals(null, 'questionHelper', $command);
        $this->assertAttributeEquals(null, 'outputFactory', $command);

        $factory = new Factory();
        $command = new class($factory) extends BaseCommand {
        };

        $this->assertAttributeInstanceOf(Factory::class, 'outputFactory', $command);
    }
}
