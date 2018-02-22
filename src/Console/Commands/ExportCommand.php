<?php

namespace Arionum\LightWalletCLI\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('export')
            ->setDescription('Export data for the wallet.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
    }
}
