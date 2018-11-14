<?php

namespace pxgamer\ArionumCLI\Commands\Alias;

use pxgamer\Arionum\ApiException;
use pxgamer\Arionum\Transaction;
use pxgamer\ArionumCLI\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function number_format;
use function preg_match;
use function strlen;
use function strtoupper;
use function time;

/**
 * Class SendCommand
 */
final class SendCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this
            ->setName('alias:send')
            ->setDescription('Send a transaction to a specific alias.')
            ->addArgument(
                'alias',
                InputArgument::REQUIRED,
                'A specific wallet alias to send to.'
            )
            ->addArgument(
                'value',
                InputArgument::REQUIRED,
                'The amount of Arionum to send.'
            )
            ->addArgument(
                'message',
                InputArgument::OPTIONAL,
                'An optional message to attach.',
                ''
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
            $alias = $input->getArgument('alias');
            $message = $input->getArgument('message');

            $aliasLength = strlen($alias);

            if (!$alias || $aliasLength < 4 || $aliasLength > 25 || !preg_match('/[a-zA-Z0-9]+/', $alias)) {
                $output->writeln('<error>ERROR: Invalid destination alias.</error>');
                return;
            }

            $alias = strtoupper($alias);

            $balance = $this->arionumClient->getBalance($this->wallet->getAddress());

            $output->writeln('<info>Transaction Information</info>');
            $output->writeln('');

            $value = $input->getArgument('value');
            $fee = $this->wallet->getFee($value);

            $total = $value + $fee;

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
                $alias,
                $message,
                $date,
                Transaction::VERSION_ALIAS_SEND
            );

            $signature = $this->wallet->sign($info, $this->wallet->getPrivateKey());

            $transaction = Transaction::makeAliasSendInstance($alias, $value);

            $transaction->setSignature($signature);
            $transaction->setPublicKey($this->wallet->getPublicKey());
            $transaction->setMessage($message);
            $transaction->setDate($date);

            $transactionId = $this->arionumClient->sendTransaction($transaction);

            $output->writeln('<info>Transaction sent successfully!</info>');
            $output->writeln('<info>ID:</info> '.$transactionId);
        } catch (ApiException $exception) {
            $output->writeln('<fg=red>'.$exception->getMessage().'</>');
        }
    }
}
