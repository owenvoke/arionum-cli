<?php

namespace Arionum\LightWalletCLI\Console;

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

        $commands[] = new Commands\BalanceCommand();
        $commands[] = new Commands\BlockCommand();
        $commands[] = new Commands\DecryptCommand();
        $commands[] = new Commands\EncryptCommand();
        $commands[] = new Commands\ExportCommand();

        return $commands;
    }
}
