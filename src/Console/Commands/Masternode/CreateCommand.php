<?php

namespace pxgamer\Arionum\Console\Commands\Masternode;

use pxgamer\Arionum\Api;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateCommand
 */
class CreateCommand extends MasternodeCommand
{
    protected function configure()
    {
        $this
            ->setName('masternode:create')
            ->setDescription('Send a masternode announcement transaction.')
            ->addArgument(
                'ip',
                InputArgument::REQUIRED,
                'The IP address for the masternode.'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $ipAddress = $input->getArgument('ip');

        if (!preg_match('/[0-9\.]+/', $ipAddress) ||
            !filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
        ) {
            $output->writeln('<error>ERROR: Invalid masternode IP address.</error>');
            $output->writeln('<comment>Provided IP: '.$ipAddress.'</comment>');
            return;
        }

        $balanceResult = Api::getBalance($this->wallet->getAddress());

        if ($balanceResult['status'] !== Api::API_STATUS_OK) {
            $output->writeln('<error>ERROR: '.$balanceResult['data'].'</error>');
            return;
        }

        $balance = $balanceResult['data'];

        $value = 100000;
        $fee = 10;
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
            $ipAddress,
            $date,
            self::COMMAND_VERSION_CREATE
        );

        $signature = $this->wallet->sign($info, $this->wallet->getPrivateKey());

        $result = Api::send(
            $this->wallet->getAddress(),
            $value,
            $signature,
            $this->wallet->getPublicKey(),
            $ipAddress,
            $date,
            self::COMMAND_VERSION_CREATE
        );

        if ($result['status'] !== Api::API_STATUS_OK) {
            $output->writeln('<error>ERROR: '.$result['data'].'</error>');
            return;
        }

        $output->writeln('<info>Masternode command sent!</info>');
        $output->writeln('<info>ID: '.$result['data'].'</info>');
    }
}
