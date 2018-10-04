<?php

namespace pxgamer\Arionum\Console\Commands;

use pxgamer\Arionum\Api;
use pxgamer\Arionum\Console\BaseCommand;
use pxgamer\Arionum\Console\Output\Format;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TransactionsCommand
 */
class TransactionsCommand extends BaseCommand
{
    protected function configure()
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
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        if ($address = $input->getArgument('address')) {
            $output->writeln('Checking transactions of the specified address: '.$address);

            if (!$this->wallet->validAddress($address)) {
                throw new \Exception('Invalid address format provided.');
            }
        }

        $result = Api::getTransactions($address ?? $this->wallet->getAddress());

        if ($result['status'] !== Api::API_STATUS_OK) {
            $output->writeln('<error>ERROR: '.$result['data'].'</error>');
            return;
        }

        $rows = [];
        foreach ($result['data'] as $key => $value) {
            $rows[] = [$value['id'], $value['dst'], $value['type'], $value['val']];
        }

        $this->outputFactory
            ->setOutput($output)
            ->writeOutput($input->getOption('output'), $rows, ['ID', 'To', 'Type', 'Amount']);
    }
}
