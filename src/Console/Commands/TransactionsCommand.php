<?php

namespace Arionum\LightWalletCLI\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TransactionsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('transactions')
            ->setDescription('Display the latest transactions.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
    }
}
