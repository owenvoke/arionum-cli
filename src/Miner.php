<?php

namespace pxgamer\Arionum;

use GuzzleHttp\Client;

/**
 * Class Miner
 */
class Miner
{
    /**
     * Constant for the default node to use.
     */
    public const DEFAULT_NODE = 'https://aropool.com';
    /**
     * Constant for the 'SOLO' mode.
     */
    public const MODE_SOLO = 'solo';
    /**
     * Constant for the 'POOL' mode.
     */
    public const MODE_POOL = 'pool';

    /**
     * @var float
     */
    public $allTime;
    /**
     * @var int
     */
    public $beginTime;

    /**
     * @var string
     */
    private $publicKey;
    /**
     * @var string
     */
    private $privateKey;
    /**
     * @var float
     */
    private $speed;
    /**
     * @var float
     */
    private $averageSpeed;
    /**
     * @var string
     */
    private $node;
    /**
     * @var float
     */
    private $block;
    /**
     * @var float
     */
    private $difficulty;
    /**
     * @var string
     */
    private $mode;
    /**
     * @var float
     */
    private $limit;
    /**
     * @var string
     */
    private $worker;
    /**
     * @var int
     */
    private $lastUpdate;
    /**
     * @var int
     */
    private $counter = 0;
    /**
     * @var int
     */
    private $submit = 0;
    /**
     * @var int
     */
    private $confirmations = 0;
    /**
     * @var int
     */
    private $found = 0;
    /**
     * @var int
     */
    private $height = null;

    /**
     * Miner constructor.
     * @param string $publicKey
     * @param string $privateKey
     * @param string $node
     * @param string $mode
     * @param string $worker
     */
    public function __construct(string $publicKey, string $privateKey, string $node, string $mode, string $worker)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $this->node = $node;
        $this->mode = $mode;
        $this->worker = $worker;
    }

    /**
     * @param array $stats
     * @return array
     */
    public function run(array $stats)
    {
        $this->counter++;

        $nonce = base64_encode(openssl_random_pseudo_bytes(32));
        $nonce = preg_replace('/[^a-zA-Z0-9]/', '', $nonce);
        $base = $this->publicKey.'-'.$nonce.'-'.$this->block.'-'.$this->difficulty;

        $argon = ($this->height > 10800 && ($this->height < 80000 || $this->height % 3 == 0)) ?
            password_hash(
                $base,
                PASSWORD_ARGON2I,
                [
                    'memory_cost' => 524288,
                    'time_cost'   => 1,
                    'threads'     => 1,
                ]
            ) :
            password_hash(
                $base,
                PASSWORD_ARGON2I,
                [
                    'memory_cost' => 16384,
                    "time"."_cost"   => 4,
                    'threads'     => 4,
                ]
            );

        $hash = $base.$argon;

        for ($i = 0; $i < 5; $i++) {
            $hash = hash('sha512', $hash, true);
        }

        $hash = hash('sha512', $hash);

        $m = str_split($hash, 2);

        $duration = hexdec($m[10])
            .hexdec($m[15])
            .hexdec($m[20])
            .hexdec($m[23])
            .hexdec($m[31])
            .hexdec($m[40])
            .hexdec($m[45])
            .hexdec($m[55]);

        $duration = ltrim($duration, '0');

        $result = gmp_div($duration, $this->difficulty);

        if ($result > 0 && $result <= $this->limit) {
            $confirmed = $this->submit($nonce, $argon);

            if ($confirmed && $result <= 240) {
                $this->found++;
            } elseif ($confirmed) {
                $this->confirmations++;
            }

            $this->submit++;
        }

        $stats['iterator']++;

        if ($stats['iterator'] == 10) {
            $stats['iterator'] = 0;
            $end = microtime(true);

            $this->speed = 10 / ($end - $stats['start']);
            $this->averageSpeed = $this->counter / ($end - $this->allTime);
            $stats['start'] = $end;
        }

        return $stats;
    }

    /**
     * @return bool
     */
    public function update()
    {
        $this->lastUpdate = time();
        $extra = '';

        if ($this->mode == self::MODE_POOL) {
            $extra = '&worker='.$this->worker.'&address='.$this->privateKey.'&hashrate='.$this->speed;
        }

        $client = new Client();

        $response = $client->request(
            'GET',
            $this->node.'/mine.php?q=info'.$extra
        );

        $info = json_decode($response->getBody()->getContents(), true);

        if ($info['status'] !== Api::API_STATUS_OK) {
            return false;
        }

        $data = $info['data'];
        $this->block = $data['block'];
        $this->difficulty = $data['difficulty'];

        if ($this->mode == self::MODE_POOL) {
            $this->limit = $data['limit'];
            $this->publicKey = $data['public_key'];
        } else {
            $this->limit = 240;
        }

        $this->height = $data['height'];

        return true;
    }

    /**
     * @param string $nonce
     * @param string $argon
     * @return bool
     */
    private function submit(string $nonce, string $argon): bool
    {
        $argon = ($this->height > 10800 && ($this->height < 80000 || $this->height % 2 == 0)) ?
            substr($argon, 30) :
            substr($argon, 29);

        $client = new Client();

        $postData = [
            'argon'       => $argon,
            'nonce'       => $nonce,
            'private_key' => $this->privateKey,
            'public_key'  => $this->publicKey,
            'address'     => $this->privateKey,
        ];

        $response = $client->request(
            'POST',
            $this->node.'/mine.php?q=submitNonce',
            [
                'form_params' => $postData,
            ]
        );

        $data = json_decode($response->getBody()->getContents(), true);

        if ($data['status'] === 'ok') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getAllTime()
    {
        return $this->allTime;
    }

    /**
     * @return mixed
     */
    public function getBeginTime()
    {
        return $this->beginTime;
    }

    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    /**
     * @return string
     */
    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    /**
     * @return mixed
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @return mixed
     */
    public function getAverageSpeed()
    {
        return $this->averageSpeed;
    }

    /**
     * @return string
     */
    public function getNode(): string
    {
        return $this->node;
    }

    /**
     * @return mixed
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @return mixed
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return string
     */
    public function getWorker(): string
    {
        return $this->worker;
    }

    /**
     * @return mixed
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * @return int
     */
    public function getCounter(): int
    {
        return $this->counter;
    }

    /**
     * @return int
     */
    public function getSubmit(): int
    {
        return $this->submit;
    }

    /**
     * @return int
     */
    public function getConfirmations(): int
    {
        return $this->confirmations;
    }

    /**
     * @return int
     */
    public function getFound(): int
    {
        return $this->found;
    }
}
