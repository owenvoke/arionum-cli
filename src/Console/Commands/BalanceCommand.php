<?php

namespace Arionum\LightWalletCLI\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BalanceCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('balance')
            ->setDescription('Print the balance of the wallet.')
            ->addArgument(
                'address',
                InputArgument::OPTIONAL,
                'A specific wallet address.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
    }
}
