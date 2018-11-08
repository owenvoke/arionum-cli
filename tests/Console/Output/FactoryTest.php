<?php

namespace pxgamer\ArionumCLI\Console\Output;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;

class FactoryTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function itCanSetTheOutputOfAFactory(): void
    {
        /** @var OutputInterface|MockObject $output */
        $output = $this->getMockBuilder(OutputInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factory = new Factory();
        $this->assertAttributeEquals(null, 'output', $factory);

        $factory->setOutput($output);
        $this->assertAttributeInstanceOf(OutputInterface::class, 'output', $factory);
    }
}
