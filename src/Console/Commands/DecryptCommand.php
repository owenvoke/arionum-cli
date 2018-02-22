<?php

namespace pxgamer\Arionum\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DecryptCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('decrypt')
            ->setDescription('Decrypt the wallet.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
    }
}
