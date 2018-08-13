<?php

namespace pxgamer\Arionum\Console\Commands;

use pxgamer\Arionum\Api;
use pxgamer\Arionum\Console\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TransactionCommand
 */
class TransactionCommand extends BaseCommand
{
    protected function configure()
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
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $result = Api::getTransaction($input->getArgument('id'));

        if ($result['status'] !== Api::API_STATUS_OK) {
            $output->writeln('<error>ERROR: '.$result['data'].'</error>');
            return;
        }

        $output->writeln('<info>Transaction Information</info>');
        $output->writeln('');

        foreach ($result['data'] as $key => $value) {
            $output->writeln('<comment>'.$key.':</comment> '.$value);
        }
    }
}
