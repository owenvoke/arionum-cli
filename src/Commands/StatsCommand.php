<?php

namespace pxgamer\ArionumCLI\Commands;

use Exception;
use pxgamer\Arionum\ApiException;
use pxgamer\ArionumCLI\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class StatsCommand extends BaseCommand
{
    public function __construct()
    {
        $this->requiresExistingWallet = false;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('stats')
            ->setDescription('Display statistics about the Arionum network.');

        parent::configure();
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        parent::execute($input, $output);

        try {
            $result = (array) $this->arionumClient->getNodeInfo();

            $output->writeln('<info>Statistics</info>');
            $output->writeln('');

            foreach ($result as $key => $value) {
                $output->writeln('<comment>'.$key.':</comment> '.$value);
            }
        } catch (ApiException $exception) {
            $output->writeln('<fg=red>'.$exception->getMessage().'</>');
        }
    }
}
