<?php

namespace pxgamer\ArionumCLI\Commands;

use pxgamer\Arionum\ApiException;
use pxgamer\ArionumCLI\BaseCommand;
use pxgamer\ArionumCLI\ArionumException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return void
     * @throws ArionumException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        parent::execute($input, $output);

        try {
            if ($address = $input->getArgument('address')) {
                $this->checkAddressValidity($address);

                $output->writeln('Checking balance for: '.$address);
            }

            $balance = $this->arionumClient->getBalance($address ?? $this->wallet->getAddress());

            $output->writeln('Balance: '.$balance);
        } catch (ApiException | ArionumException $exception) {
            $output->writeln('<fg=red>'.$exception->getMessage().'</>');
        }
    }

    /**
     * @param  string  $address
     * @throws ArionumException
     */
    private function checkAddressValidity(string $address): void
    {
        if (! $this->wallet->validAddress($address)) {
            throw new ArionumException('Invalid address format provided.');
        }
    }
}
