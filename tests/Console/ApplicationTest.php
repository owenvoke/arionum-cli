<?php

namespace pxgamer\Arionum\Console;

use PHPUnit\Framework\TestCase;
use pxgamer\Arionum\Console\Output\Factory;

class ApplicationTest extends TestCase
{
    public function testConstructorDefault()
    {
        $app = new Application();

        $this->assertAttributeSame(Application::NAME, 'name', $app);
        $this->assertAttributeSame('source', 'version', $app);
        $this->assertAttributeInstanceOf(Factory::class, 'outputFactory', $app);
    }

    public function testConstructor()
    {
        $app = new Application('foo', 'bar');

        $this->assertAttributeSame('foo', 'name', $app);
        $this->assertAttributeSame('bar', 'version', $app);
        $this->assertAttributeInstanceOf(Factory::class, 'outputFactory', $app);
    }
}