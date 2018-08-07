<?php

namespace pxgamer\Arionum\Console\Commands\Masternode;

/**
 * Interface MasternodeCommandInterface
 */
interface MasternodeCommandInterface
{
    /**
     * The transaction version to create a masternode.
     */
    public const COMMAND_VERSION_CREATE = 100;
    /**
     * The transaction version to pause a masternode.
     */
    public const COMMAND_VERSION_PAUSE = 101;
    /**
     * The transaction version to resume a masternode.
     */
    public const COMMAND_VERSION_RESUME = 102;
    /**
     * The transaction version to release a masternode.
     */
    public const COMMAND_VERSION_RELEASE = 103;
    /**
     * The default fee for masternode commands.
     */
    public const DEFAULT_COMMAND_FEE = 0.00000001;
    /**
     * The default value for masternode commands.
     */
    public const DEFAULT_COMMAND_VALUE = 0.00000001;
}
