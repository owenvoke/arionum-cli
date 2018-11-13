<?php

namespace pxgamer\ArionumCLI\Commands;

use pxgamer\Arionum\ApiException;
use pxgamer\ArionumCLI\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TransactionCommand
 */
final class TransactionCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this
            ->setName('transaction')
            ->setDescription('Display data about a specific transaction.')
            ->addArgument(
                'id',
                InputArgument::REQUIRED,
                'A specific transaction id to view.'
            );

        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        parent::execute($input, $output);

        try {
            $result = $this->arionumClient->getTransaction($input->getArgument('id'));

            $output->writeln('<info>Transaction Information</info>');
            $output->writeln('');

            foreach ($result['data'] as $key => $value) {
                $output->writeln('<comment>'.$key.':</comment> '.$value);
            }
        } catch (ApiException $exception) {
            $output->writeln('<fg=red>'.$exception->getMessage().'</>');
        }
    }
}
