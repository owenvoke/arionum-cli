<?php

namespace pxgamer\ArionumCLI;

use function file;
use function array_rand;

final class Api
{
    /**
     * The URI for retrieving peer nodes.
     * @link https://api.arionum.com/peers.txt
     */
    public const PEERS_URI = 'https://api.arionum.com/peers.txt';

    /**
     * @return string
     * @throws ArionumException
     */
    public static function getPeer(): string
    {
        /** @var string[] $peerList */
        $peerList = file(self::PEERS_URI, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);

        if ($peer = $peerList[array_rand($peerList)] ?? null) {
            return $peer;
        }

        throw new ArionumException('No peer uris were available.');
    }
}
