<?php

namespace pxgamer\Arionum\Console\Commands\Masternode;

use pxgamer\Arionum\Api;
use pxgamer\Arionum\Console\BaseCommand;

/**
 * Class MasternodeCommandInterface
 */
class MasternodeCommand extends BaseCommand
{
    /**
     * The transaction version to create a masternode.
     */
    protected const COMMAND_VERSION_CREATE = 100;
    /**
     * The transaction version to pause a masternode.
     */
    protected const COMMAND_VERSION_PAUSE = 101;
    /**
     * The transaction version to resume a masternode.
     */
    protected const COMMAND_VERSION_RESUME = 102;
    /**
     * The transaction version to release a masternode.
     */
    protected const COMMAND_VERSION_RELEASE = 103;
    /**
     * The default fee for masternode commands.
     */
    protected const DEFAULT_COMMAND_FEE = 0.00000001;
    /**
     * The default value for masternode commands.
     */
    protected const DEFAULT_COMMAND_VALUE = 0.00000001;

    /**
     * @param int $commandType
     * @return array
     * @throws \Exception
     */
    protected function sendCommand(int $commandType)
    {
        $balanceResult = Api::getBalance($this->wallet->getAddress());

        if ($balanceResult['status'] !== Api::API_STATUS_OK) {
            throw new \Exception('ERROR: '.$balanceResult['data']);
        }

        $balance = $balanceResult['data'];

        $total = self::DEFAULT_COMMAND_VALUE + self::DEFAULT_COMMAND_FEE;

        $value = number_format(self::DEFAULT_COMMAND_VALUE, 8, '.', '');
        $fee = number_format(self::DEFAULT_COMMAND_FEE, 8, '.', '');

        if ($balance < $total) {
            throw new \Exception('ERROR: Not enough funds in balance.');
        }

        $date = time();

        $info = $this->wallet->generateSignature(
            $value,
            $fee,
            $this->wallet->getAddress(),
            '',
            $date,
            self::COMMAND_VERSION_PAUSE
        );

        $signature = $this->wallet->sign($info, $this->wallet->getPrivateKey());

        $result = Api::send(
            $this->wallet->getAddress(),
            $value,
            $signature,
            $this->wallet->getPublicKey(),
            '',
            $date,
            self::COMMAND_VERSION_PAUSE
        );

        if ($result['status'] !== Api::API_STATUS_OK) {
            throw new \Exception('ERROR: '.$result['data']);
        }

        return $result;
    }
}
