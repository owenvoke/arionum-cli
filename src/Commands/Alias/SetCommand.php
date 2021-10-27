<?php

namespace pxgamer\ArionumCLI\Commands\Alias;

use Exception;
use function number_format;
use function preg_match;
use pxgamer\Arionum\ApiException;
use pxgamer\Arionum\Transaction;
use pxgamer\ArionumCLI\BaseCommand;
use function strlen;
use function strtoupper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function time;

final class SetCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this
            ->setName('alias:set')
            ->setDescription('Set the alias for the current wallet.')
            ->addArgument(
                'alias',
                InputArgument::REQUIRED,
                'The alias to use for the wallet.'
            );

        parent::configure();
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return void
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        parent::execute($input, $output);

        try {
            $alias = $input->getArgument('alias');

            $aliasLength = strlen($alias);
            if (! $alias || $aliasLength < 4 || $aliasLength > 25 || ! preg_match('/[a-zA-Z0-9]+/', $alias)) {
                $output->writeln('<error>ERROR: Invalid destination alias.</error>');

                return;
            }

            $alias = strtoupper($alias);

            $balance = $this->arionumClient->getBalance($this->wallet->getAddress());

            $total = Transaction::VALUE_ALIAS_SET + Transaction::FEE_ALIAS_SET;

            $value = number_format(Transaction::VALUE_ALIAS_SET, 8, '.', '');
            $fee = number_format(Transaction::FEE_ALIAS_SET, 8, '.', '');

            if ($balance < $total) {
                $output->writeln('<error>ERROR: Not enough funds in balance.</error>');

                return;
            }

            $date = time();
            $address = $this->wallet->getAddress();

            $info = $this->wallet->generateSignature(
                $value,
                $fee,
                $address,
                $alias,
                $date,
                Transaction::VERSION_ALIAS_SET
            );

            $signature = $this->wallet->sign($info, $this->wallet->getPrivateKey());

            $transaction = Transaction::makeAliasSetInstance($address, $alias);

            $transaction->setSignature($signature);
            $transaction->setPublicKey($this->wallet->getPublicKey());
            $transaction->setMessage($alias);
            $transaction->setDate($date);

            $transactionId = $this->arionumClient->sendTransaction($transaction);

            $output->writeln('<info>Transaction sent successfully!</info>');
            $output->writeln('<info>ID:</info> '.$transactionId);
        } catch (ApiException $exception) {
            $output->writeln('<fg=red>'.$exception->getMessage().'</>');
        }
    }
}
