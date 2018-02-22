<?php

namespace Arionum\LightWalletCLI\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EncryptCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('encrypt')
            ->setDescription('Encrypt the wallet.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
    }
}
