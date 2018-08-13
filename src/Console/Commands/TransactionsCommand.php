<?php

namespace pxgamer\Arionum\Console\Commands;

use pxgamer\Arionum\Api;
use pxgamer\Arionum\Console\BaseCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
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
            ->setDescription('Display the latest transactions.');

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

        $result = Api::getTransactions($this->wallet->getAddress());

        if ($result['status'] !== Api::API_STATUS_OK) {
            $output->writeln('<error>ERROR: '.$result['data'].'</error>');
            return;
        }

        $rows = [];

        foreach ($result['data'] as $key => $value) {
            $rows[] = [$value['id'], $value['dst'], $value['type'], $value['val']];
        }

        $table = new Table($output);

        $table
            ->setHeaders(['ID', 'To', 'Type', 'Amount'])
            ->setRows($rows);

        $table->render();
    }
}
