<?php

namespace pxgamer\ArionumCLI;

use pxgamer\ArionumCLI\Output\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Application as BaseApplication;

final class Application extends BaseApplication
{
    public const NAME = 'Arionum';
    public const VERSION = '@git-version@';

    /** @var Factory */
    private $outputFactory;

    public function __construct(?string $name = null, ?string $version = null)
    {
        $this->outputFactory = new Factory();

        if (! $version) {
            $version = static::VERSION === '@'.'git-version@' ?
                'source' :
                static::VERSION;
        }

        parent::__construct(
            $name ?: static::NAME,
            $version
        );
    }

    /**
     * @return array<Command>
     */
    protected function getDefaultCommands(): array
    {
        $commands = parent::getDefaultCommands();

        // General Commands
        $commands[] = new Commands\BalanceCommand();
        $commands[] = new Commands\BlockCommand();
        $commands[] = new Commands\DecryptCommand();
        $commands[] = new Commands\EncryptCommand();
        $commands[] = new Commands\ExportCommand();
        $commands[] = new Commands\GenerateCommand();
        $commands[] = new Commands\SendCommand();
        $commands[] = new Commands\StatsCommand();
        $commands[] = new Commands\TransactionCommand();
        $commands[] = new Commands\TransactionsCommand($this->outputFactory);

        // Alias Commands
        $commands[] = new Commands\Alias\SendCommand();
        $commands[] = new Commands\Alias\SetCommand();

        // Masternode Commands
        $commands[] = new Commands\Masternode\CreateCommand();
        $commands[] = new Commands\Masternode\PauseCommand();
        $commands[] = new Commands\Masternode\ReleaseCommand();
        $commands[] = new Commands\Masternode\ResumeCommand();

        return $commands;
    }
}
