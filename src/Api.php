<?php

namespace pxgamer\ArionumCLI;

use GuzzleHttp\Client;
use function file;
use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;
use function shuffle;
use function strlen;
use function trim;

/**
 * Class Api
 */
class Api
{
    /**
     * The URI for retrieving peer nodes.
     * @link https://api.arionum.com/peers.txt
     */
    public const PEERS_URI = 'https://api.arionum.com/peers.txt';
    /**
     * The API status code for a successful response.
     */
    public const API_STATUS_OK = 'ok';

    /**
     * @var null|string
     */
    public static $customPeer;

    /**
     * @param string $url
     * @param array  $data
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function post(string $url, $data = [])
    {
        $peer = self::getPeer();

        if (empty($peer)) {
            return false;
        }

        $client = new Client();

        $postData = [
            'data' => json_encode($data),
            'coin' => 'arionum',
        ];

        $response = $client->request(
            'POST',
            $peer.$url,
            [
                'timeout'     => 300,
                'form_params' => $postData,
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $address
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getBalance($address)
    {
        return self::post(
            '/api.php?q=getPendingBalance',
            [
                'account' => $address,
            ]
        );
    }

    /**
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getCurrentBlock()
    {
        return self::post('/api.php?q=currentBlock');
    }

    /**
     * @param string $id
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getTransaction(string $id)
    {
        return self::post(
            '/api.php?q=getTransaction',
            [
                'transaction' => $id,
            ]
        );
    }

    /**
     * @param string $address
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getTransactions(string $address)
    {
        return self::post(
            '/api.php?q=getTransactions',
            [
                'account' => $address,
            ]
        );
    }

    /**
     * @param string $address
     * @param float  $value
     * @param string $signature
     * @param string $publicKey
     * @param string $message
     * @param int    $date
     * @param int    $version
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function send(
        string $address,
        float $value,
        string $signature,
        string $publicKey,
        string $message,
        int $date,
        int $version = 1
    ) {
        return self::post('/api.php?q=send', [
            'dst'        => $address,
            'val'        => $value,
            'signature'  => $signature,
            'public_key' => $publicKey,
            'version'    => $version,
            'message'    => $message,
            'date'       => $date,
        ]);
    }

    /**
     * @return bool|string
     */
    private static function getPeer()
    {
        if (self::$customPeer) {
            return self::$customPeer;
        }

        $peerList = file(self::PEERS_URI);
        shuffle($peerList);

        foreach ($peerList as $x) {
            if (strlen(trim($x)) > 5) {
                return trim($x);
                break;
            }
        }

        return false;
    }
}
