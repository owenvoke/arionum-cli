<?php

namespace pxgamer\ArionumCLI\Console\Commands\Alias;

use pxgamer\ArionumCLI\Api;
use pxgamer\ArionumCLI\Console\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function number_format;
use function preg_match;
use function strlen;
use function strtoupper;
use function time;

/**
 * Class SetCommand
 */
class SetCommand extends BaseCommand
{
    private const ALIAS_SET_VERSION = 3;

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
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $alias = $input->getArgument('alias');

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

        $fee = 10;
        $value = 0.00000001;
        $total = $value + $fee;

        $value = number_format($value, 8, '.', '');
        $fee = number_format($fee, 8, '.', '');

        if ($balance < $total) {
            $output->writeln('<error>ERROR: Not enough funds in balance.</error>');
            return;
        }

        $date = time();

        $info = $this->wallet->generateSignature(
            $value,
            $fee,
            $this->wallet->getAddress(),
            $alias,
            $date,
            self::ALIAS_SET_VERSION
        );

        $signature = $this->wallet->sign($info, $this->wallet->getPrivateKey());

        $result = Api::send(
            $this->wallet->getAddress(),
            $value,
            $signature,
            $this->wallet->getPublicKey(),
            $alias,
            $date,
            self::ALIAS_SET_VERSION
        );

        if ($result['status'] !== Api::API_STATUS_OK) {
            $output->writeln('<error>ERROR: '.$result['data'].'</error>');
            return;
        }

        $output->writeln('<info>Transaction sent successfully!</info>');
        $output->writeln('<info>ID: '.$result['data'].'</info>');
    }
}
