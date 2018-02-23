<?php

namespace pxgamer\Arionum\Console\Commands;

use pxgamer\Arionum\Api;
use pxgamer\Arionum\Console\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BalanceCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('balance')
            ->setDescription('Print the balance of the wallet.')
            ->addArgument(
                'address',
                InputArgument::OPTIONAL,
                'A specific wallet address.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        if ($address = $input->getArgument('address')) {
            $output->writeln('Checking balance of the specified address: '.$address);

            if (!$this->wallet->validAddress($address)) {
                throw new \Exception('Invalid address format provided.');
            }
        }

        $result = Api::getBalance($address ?? $this->wallet->getAddress());

        if ($result['status'] != 'ok') {
            $output->writeln('<error>ERROR: '.$result['data'].'</error>');
        } else {
            $output->writeln('Balance: '.$result['data']);
        }
    }
}
