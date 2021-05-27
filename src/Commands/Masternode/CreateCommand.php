<?php

namespace pxgamer\ArionumCLI\Commands\Masternode;

use Exception;
use function filter_var;
use function preg_match;
use pxgamer\Arionum\ApiException;
use pxgamer\Arionum\Transaction;
use pxgamer\ArionumCLI\ArionumException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function time;

final class CreateCommand extends MasternodeCommand
{
    protected function configure(): void
    {
        $this
            ->setName('masternode:create')
            ->setDescription('Send a masternode announcement transaction.')
            ->addArgument(
                'ip',
                InputArgument::REQUIRED,
                'The IP address for the masternode.'
            );

        parent::configure();
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        parent::execute($input, $output);

        try {
            $ipAddress = $input->getArgument('ip');
            $address = $this->wallet->getAddress();

            if (! preg_match('/[0-9\.]+/', $ipAddress) ||
                ! filter_var($ipAddress, FILTER_VALIDATE_IP)
            ) {
                $output->writeln('<error>ERROR: Invalid masternode IP address.</error>');
                $output->writeln('<comment>Provided IP: '.$ipAddress.'</comment>');

                return;
            }

            $date = time();
            $signature = $this->returnCommandSignature(Transaction::VERSION_MASTERNODE_CREATE, $date, $ipAddress);

            $transaction = Transaction::makeMasternodeCreateInstance($ipAddress, $address);

            $transaction->setSignature($signature);
            $transaction->setPublicKey($this->wallet->getPublicKey());
            $transaction->setDate($date);

            $transactionId = $this->arionumClient->sendTransaction($transaction);

            $output->writeln('<info>Masternode `create` command sent!</info>');
            $output->writeln('<comment>Transaction id:</comment> '.$transactionId);
        } catch (ApiException | ArionumException $exception) {
            $output->writeln('<fg=red>'.$exception->getMessage().'</>');
        }
    }
}
