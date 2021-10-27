<?php

namespace pxgamer\ArionumCLI\Commands;

use Exception;
use pxgamer\Arionum\ApiException;
use pxgamer\ArionumCLI\ArionumException;
use pxgamer\ArionumCLI\BaseCommand;
use pxgamer\ArionumCLI\Output\Format;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class TransactionsCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this
            ->setName('transactions')
            ->setDescription('Display the latest transactions.')
            ->addArgument(
                'address',
                InputArgument::OPTIONAL,
                'A specific wallet address.'
            )
            ->addOption(
                'output',
                null,
                InputOption::VALUE_OPTIONAL,
                'The output format (table, xml, json, csv)',
                Format::TABLE
            );

        parent::configure();
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return void
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        parent::execute($input, $output);

        if ($address = $input->getArgument('address')) {
            $output->writeln('Checking transactions of the specified address: '.$address);

            if (! $this->wallet->validAddress($address)) {
                throw new ArionumException('Invalid address format provided.');
            }
        }

        try {
            $transactions = $this->arionumClient->getTransactions($address ?? $this->wallet->getAddress());

            $rows = [];
            foreach ($transactions as $transaction) {
                $rows[] = [$transaction->id, $transaction->dst, $transaction->type, $transaction->val];
            }

            $this->outputFactory
                ->setOutput($output)
                ->writeOutput($input->getOption('output'), $rows, ['ID', 'To', 'Type', 'Amount']);
        } catch (ApiException $exception) {
            $output->writeln('<fg=red>'.$exception->getMessage().'</>');
        }
    }
}
