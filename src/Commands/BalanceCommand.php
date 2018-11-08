<?php

namespace pxgamer\ArionumCLI\Commands;

use GuzzleHttp\Exception\GuzzleException;
use pxgamer\ArionumCLI\Api;
use pxgamer\ArionumCLI\ArionumException;
use pxgamer\ArionumCLI\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BalanceCommand
 */
final class BalanceCommand extends BaseCommand
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
     * @throws ArionumException
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        if ($address = $input->getArgument('address')) {
            $output->writeln('Checking balance of the specified address: '.$address);

            if (!$this->wallet->validAddress($address)) {
                throw new ArionumException('Invalid address format provided.');
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
