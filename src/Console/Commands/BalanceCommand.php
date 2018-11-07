<?php

namespace pxgamer\Arionum\Console\Commands;

use Exception;
use pxgamer\Arionum\Api;
use pxgamer\Arionum\Console\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BalanceCommand
 */
class BalanceCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this
            ->setName('balance')
            ->setDescription('Print the balance of the wallet.')
            ->addArgument(
                'address',
                InputArgument::OPTIONAL,
                'A specific wallet address.'
            );

        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        if ($address = $input->getArgument('address')) {
            $output->writeln('Checking balance of the specified address: '.$address);

            if (!$this->wallet->validAddress($address)) {
                throw new Exception('Invalid address format provided.');
            }
        }

        $result = Api::getBalance($address ?? $this->wallet->getAddress());

        if ($result['status'] !== Api::API_STATUS_OK) {
            $output->writeln('<error>ERROR: '.$result['data'].'</error>');
            return;
        }

        $output->writeln('Balance: '.$result['data']);
    }
}
