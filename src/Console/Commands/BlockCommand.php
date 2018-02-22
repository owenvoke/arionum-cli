<?php

namespace pxgamer\Arionum\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BlockCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('block')
            ->setDescription('Display data about the current block.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
    }
}
