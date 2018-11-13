<?php

namespace pxgamer\ArionumCLI\Commands;

use pxgamer\Arionum\ApiException;
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
     * @return void
     * @throws ArionumException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        parent::execute($input, $output);

        if ($address = $input->getArgument('address')) {
            $output->writeln('Checking balance of the specified address: '.$address);

            if (!$this->wallet->validAddress($address)) {
                throw new ArionumException('Invalid address format provided.');
            }
        }

        try {
            $result = $this->arionumClient->getBalance($address ?? $this->wallet->getAddress());

            $output->writeln('Balance: '.$result);
        } catch (ApiException $exception) {
            $output->writeln('<fg=red>'.$exception->getMessage().'</>');
        }
    }
}
