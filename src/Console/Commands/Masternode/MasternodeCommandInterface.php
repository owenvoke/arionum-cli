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
}
