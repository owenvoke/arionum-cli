<?php

namespace pxgamer\Arionum\Console;

use Symfony\Component\Console\Application as BaseApplication;

/**
 * Class Application
 */
class Application extends BaseApplication
{
    const NAME = 'Arionum Light Wallet';
    const VERSION = '@git-version@';

    /**
     * Application constructor.
     *
     * @param null $name
     * @param null $version
     */
    public function __construct($name = null, $version = null)
    {
        parent::__construct(
            $name ?: static::NAME,
            $version ?: (static::VERSION === '@'.'git-version@' ? 'source' : static::VERSION)
        );
    }

    /**
     * @return array|\Symfony\Component\Console\Command\Command[]
     */
    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();

        // General Commands
        $commands[] = new Commands\BalanceCommand();
        $commands[] = new Commands\BlockCommand();
        $commands[] = new Commands\DecryptCommand();
        $commands[] = new Commands\EncryptCommand();
        $commands[] = new Commands\ExportCommand();
        $commands[] = new Commands\GenerateCommand();
        $commands[] = new Commands\MinerCommand();
        $commands[] = new Commands\SendCommand();
        $commands[] = new Commands\TransactionCommand();
        $commands[] = new Commands\TransactionsCommand();

        // Alias Commands
        $commands[] = new Commands\Alias\SetCommand();

        return $commands;
    }
}
