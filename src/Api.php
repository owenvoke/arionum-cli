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

        $postdata = http_build_query(
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
                    'content' => $postdata,
                ],
        ];

        $context = stream_context_create($opts);
        $result = file_get_contents($peer.$url, false, $context);
        $res = json_decode($result, true);

        return $res;
    }
}
