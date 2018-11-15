<?php

namespace pxgamer\ArionumCLI\Commands\Masternode;

use pxgamer\Arionum\ApiException;
use pxgamer\Arionum\Transaction;
use pxgamer\ArionumCLI\ArionumException;
use pxgamer\ArionumCLI\BaseCommand;

/**
 * Class MasternodeCommandInterface
 */
class MasternodeCommand extends BaseCommand
{
    /**
     * @param int    $commandType
     * @param int    $date
     * @param string $message
     * @return string
     * @throws ApiException
     * @throws ArionumException
     * @throws \Exception
     */
    protected function returnCommandSignature(int $commandType, int $date, string $message = ''): string
    {
        $isCreateCommand = $commandType === Transaction::VERSION_MASTERNODE_CREATE;

        $balance = $this->arionumClient->getBalance($this->wallet->getAddress());
        $total = $isCreateCommand ?
            Transaction::VALUE_MASTERNODE_CREATE + Transaction::FEE_MASTERNODE_CREATE :
            Transaction::VALUE_MASTERNODE_COMMAND + Transaction::FEE_MASTERNODE_COMMAND;

        if ($balance < $total) {
            throw new ArionumException('ERROR: Not enough funds in balance.');
        }

        $info = $this->wallet->generateSignature(
            $this->formatFloat(
                $isCreateCommand ? Transaction::VALUE_MASTERNODE_CREATE : Transaction::VALUE_MASTERNODE_COMMAND
            ),
            $this->formatFloat(
                $isCreateCommand ? Transaction::FEE_MASTERNODE_CREATE : Transaction::FEE_MASTERNODE_COMMAND
            ),
            $this->wallet->getAddress(),
            $message,
            $date,
            $commandType
        );

        return $this->wallet->sign($info, $this->wallet->getPrivateKey());
    }

    /**
     * Format a float to a string for signing.
     *
     * @param float $value
     * @return string
     */
    private function formatFloat(float $value): string
    {
        return number_format($value, 8, '.', '');
    }
}
