<?php

namespace pxgamer\Arionum\Console\Commands;

use pxgamer\Arionum\Api;
use pxgamer\Arionum\Console\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $result = Api::getTransaction($input->getArgument('id'));

        if ($result['status'] !== 'ok') {
            $output->writeln('<error>ERROR: '.$result['data'].'</error>');
        } else {
            $output->writeln('<info>Transaction Information</info>');
            $output->writeln('');

            foreach ($result['data'] as $key => $value) {
                $output->writeln('<comment>'.$key.':</comment> '.$value);
            }
        }
    }
}
