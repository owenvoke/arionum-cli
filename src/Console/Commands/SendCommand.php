<?php

namespace Arionum\LightWalletCLI\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('send')
            ->setDescription('Send a transaction with an optional message.')
            ->addArgument(
                'address',
                InputArgument::REQUIRED,
                'A specific wallet address to send to.'
            )
            ->addArgument(
                'value',
                InputArgument::REQUIRED,
                'The amount of Arionum to send.'
            )
            ->addArgument(
                'message',
                InputArgument::OPTIONAL,
                'An optional message to attach.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
    }
}
