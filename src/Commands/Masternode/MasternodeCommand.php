<?php

namespace pxgamer\ArionumCLI\Commands\Masternode;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use pxgamer\ArionumCLI\Api;
use pxgamer\ArionumCLI\ArionumException;
use pxgamer\ArionumCLI\BaseCommand;
use function number_format;
use function time;

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
     * @throws Exception
     * @throws GuzzleException
     */
    protected function sendCommand(int $commandType): array
    {
        $balanceResult = Api::getBalance($this->wallet->getAddress());

        if ($balanceResult['status'] !== Api::API_STATUS_OK) {
            throw new ArionumException('ERROR: '.$balanceResult['data']);
        }

        $balance = $balanceResult['data'];

        $total = self::DEFAULT_COMMAND_VALUE + self::DEFAULT_COMMAND_FEE;

        $value = number_format(self::DEFAULT_COMMAND_VALUE, 8, '.', '');
        $fee = number_format(self::DEFAULT_COMMAND_FEE, 8, '.', '');

        if ($balance < $total) {
            throw new ArionumException('ERROR: Not enough funds in balance.');
        }

        $date = time();

        $info = $this->wallet->generateSignature(
            $value,
            $fee,
            $this->wallet->getAddress(),
            '',
            $date,
            $commandType
        );

        $signature = $this->wallet->sign($info, $this->wallet->getPrivateKey());

        $result = Api::send(
            $this->wallet->getAddress(),
            $value,
            $signature,
            $this->wallet->getPublicKey(),
            '',
            $date,
            $commandType
        );

        if ($result['status'] !== Api::API_STATUS_OK) {
            throw new ArionumException('ERROR: '.$result['data']);
        }

        return $result;
    }
}
