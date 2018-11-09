<?php

namespace pxgamer\ArionumCLI\Commands\Alias;

use GuzzleHttp\Exception\GuzzleException;
use pxgamer\ArionumCLI\Api;
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
    private const ALIAS_SEND_VERSION = 2;

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
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        parent::execute($input, $output);

        $alias = $input->getArgument('alias');
        $message = $input->getArgument('message');

        $aliasLength = strlen($alias);
        if (!$alias || $aliasLength < 4 || $aliasLength > 25 || !preg_match('/[a-zA-Z0-9]+/', $alias)) {
            $output->writeln('<error>ERROR: Invalid destination alias.</error>');
            return;
        }

        $alias = strtoupper($alias);

        $balanceResult = Api::getBalance($this->wallet->getAddress());

        if ($balanceResult['status'] !== Api::API_STATUS_OK) {
            $output->writeln('<error>ERROR: '.$balanceResult['data'].'</error>');
            return;
        }

        $balance = $balanceResult['data'];

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
            $alias,
            $message,
            $date,
            self::ALIAS_SEND_VERSION
        );

        $signature = $this->wallet->sign($info, $this->wallet->getPrivateKey());

        $result = Api::send(
            $alias,
            $value,
            $signature,
            $this->wallet->getPublicKey(),
            $message,
            $date,
            self::ALIAS_SEND_VERSION
        );

        if ($result['status'] !== Api::API_STATUS_OK) {
            $output->writeln('<error>ERROR: '.$result['data'].'</error>');
            return;
        }

        $output->writeln('<info>Transaction sent successfully!</info>');
        $output->writeln('<info>ID: '.$result['data'].'</info>');
    }
}
