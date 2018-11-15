<?php

namespace pxgamer\ArionumCLI\Commands;

use pxgamer\Arionum\ApiException;
use pxgamer\Arionum\Transaction;
use pxgamer\ArionumCLI\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function number_format;
use function time;

/**
 * Class SendCommand
 */
final class SendCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this
            ->setName('send')
            ->setDescription('Send a transaction with an optional message.')
            ->addArgument(
                'address',
                InputArgument::REQUIRED,
                'A specific wallet address to send to.'
            )
            ->addArgument(
                'value',
                InputArgument::REQUIRED,
                'The amount of Arionum to send.'
            )
            ->addArgument(
                'message',
                InputArgument::OPTIONAL,
                'An optional message to attach.'
            );

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
            $balance = $this->arionumClient->getBalance($this->wallet->getAddress());

            $output->writeln('<info>Transaction Information</info>');
            $output->writeln('');

            $fee = $this->wallet->getFee($input->getArgument('value'));

            $total = $input->getArgument('value') + $fee;

            $value = number_format($input->getArgument('value'), 8, '.', '');
            $fee = number_format($fee, 8, '.', '');

            if ($balance < $total) {
                $output->writeln('<error>ERROR: Not enough funds in balance.</error>');
                return;
            }

            $date = time();

            $info = $this->wallet->generateSignature(
                $value,
                $fee,
                $input->getArgument('address'),
                $input->getArgument('message'),
                $date
            );

            $signature = $this->wallet->sign($info, $this->wallet->getPrivateKey());

            $transaction = new Transaction();

            $transaction->setDestinationAddress($input->getArgument('address'));
            $transaction->setValue($value);
            $transaction->setSignature($signature);
            $transaction->setPublicKey($this->wallet->getPublicKey());
            $transaction->setMessage($input->getArgument('message') ?? '');
            $transaction->setDate($date);

            $transactionId = $this->arionumClient->sendTransaction($transaction);

            $output->writeln('<info>Transaction sent successfully!</info>');
            $output->writeln('<info>Transaction id:</info> '.$transactionId);
        } catch (ApiException $exception) {
            $output->writeln('<fg=red>'.$exception->getMessage().'</>');
        }
    }
}
