<?php

namespace pxgamer\Arionum;

/**
 * Class Api
 */
class Api
{
    /**
     * The URI for retrieving peer nodes.
     * @link https://api.arionum.com/peers.txt
     */
    const PEERS_URI = 'https://api.arionum.com/peers.txt';
    /**
     * The API status code for a successful response.
     */
    public const API_STATUS_OK = 'ok';

    /**
     * @param string $url
     * @param array  $data
     * @return bool|mixed
     */
    public static function post(string $url, $data = [])
    {
        $f = file(self::PEERS_URI);
        shuffle($f);

        foreach ($f as $x) {
            if (strlen(trim($x)) > 5) {
                $peer = trim($x);
                break;
            }
        }

        if (empty($peer)) {
            return false;
        }

        $postData = http_build_query(
            [
                'data' => json_encode($data),
                'coin' => 'arionum',
            ]
        );

        $opts = [
            'http' =>
                [
                    'timeout' => '300',
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postData,
                ],
        ];

        $context = stream_context_create($opts);
        $result = file_get_contents($peer.$url, false, $context);

        return json_decode($result, true);
    }

    /**
     * @param string $address
     * @return bool|mixed
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
     */
    public static function getCurrentBlock()
    {
        return self::post('/api.php?q=currentBlock');
    }

    /**
     * @param string $id
     * @return bool|mixed
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
}
