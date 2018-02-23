<?php

namespace pxgamer\Arionum\Console\Commands;

use pxgamer\Arionum\Console\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ExportCommand
 */
class ExportCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('export')
            ->setDescription('Export data for the wallet.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $output->writeln('<info>Your public key is:</info> '.$this->wallet->getPublicKey());
        $output->writeln('<info>Your private key is:</info> '.$this->wallet->getPrivateKey());
    }
}