<?php

namespace pxgamer\Arionum\Console\Commands;

use pxgamer\Arionum\Console\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MinerCommand
 *
 * @deprecated 2.0.0 The PHP miner is virtually redundant for Arionum.
 */
class MinerCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this
            ->setName('miner')
            ->setAliases(['mine']);

        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('<fg=red>This command is now redundant and will be removed in v2.0.0.</>');
    }
}
