<?php

namespace Arionum\LightWalletCLI\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TransactionCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('transaction')
            ->setDescription('Display data about a specific transaction.')
            ->addArgument(
                'id',
                InputArgument::REQUIRED,
                'A specific transaction id to view.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
    }
}
