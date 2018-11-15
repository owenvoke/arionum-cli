<?php

namespace pxgamer\ArionumCLI\Commands\Masternode;

use pxgamer\Arionum\ApiException;
use pxgamer\Arionum\Transaction;
use pxgamer\ArionumCLI\ArionumException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ReleaseCommand
 */
final class ReleaseCommand extends MasternodeCommand
{
    protected function configure(): void
    {
        $this
            ->setName('masternode:release')
            ->setDescription('Close the masternode and return the funds.');

        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        parent::execute($input, $output);

        try {
            $date = time();
            $signature = $this->returnCommandSignature(Transaction::VERSION_MASTERNODE_RELEASE, $date);

            $transaction = Transaction::makeMasternodeReleaseInstance($this->wallet->getAddress());

            $transaction->setSignature($signature);
            $transaction->setPublicKey($this->wallet->getPublicKey());
            $transaction->setDate($date);

            $transactionId = $this->arionumClient->sendTransaction($transaction);

            $output->writeln('<info>Masternode `release` command sent!</info>');
            $output->writeln('<comment>Transaction id:</comment> '.$transactionId);
        } catch (ApiException | ArionumException $exception) {
            $output->writeln('<error>'.$exception->getMessage().'</error>');
        }
    }
}
