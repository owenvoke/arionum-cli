<?php

namespace pxgamer\ArionumCLI;

use StephenHill\Base58;
use function base64_decode;
use function base64_encode;
use function explode;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function hash;
use function implode;
use function openssl_encrypt;
use function openssl_pkey_export;
use function openssl_pkey_get_details;
use function openssl_pkey_get_private;
use function openssl_pkey_new;
use function openssl_sign;
use function preg_match;
use function str_replace;
use function str_split;
use function strlen;
use function substr;

/**
 * Class Wallet
 */
final class Wallet
{
    /**
     * The default wallet file name.
     */
    public const WALLET_NAME = 'wallet.aro';
    public const MIN_KEY_LENGTH = 20;

    /**
     * @var string
     */
    private $path;
    /**
     * @var bool
     */
    private $exists;
    /**
     * @var bool|string
     */
    private $rawData;
    /**
     * @var string
     */
    private $address;
    /**
     * @var string
     */
    private $publicKey;
    /**
     * @var string
     */
    private $privateKey;

    /**
     * Wallet constructor.
     * @param string $path
     */
    public function __construct(string $path = self::WALLET_NAME)
    {
        $this->path = $path;
        $this->exists = file_exists($this->path);

        if ($this->exists) {
            $this->rawData = file_get_contents($this->path);
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function create(): string
    {
        $args = [
            'curve_name'       => 'secp256k1',
            'private_key_type' => OPENSSL_KEYTYPE_EC,
        ];

        $key1 = openssl_pkey_new($args);
        openssl_pkey_export($key1, $pvKey);

        $privateKey = $this->pem2coin($pvKey);

        $pub = openssl_pkey_get_details($key1);

        $publicKey = $this->pem2coin($pub['key']);

        if (strlen($privateKey) < self::MIN_KEY_LENGTH || strlen($publicKey) < self::MIN_KEY_LENGTH) {
            throw new \Exception('Failed to create the EC key pair. Please check the openssl binaries.');
        }

        return 'arionum:'.$privateKey.':'.$publicKey;
    }

    /**
     * @return bool
     */
    public function isEncrypted(): bool
    {
        return strpos($this->rawData, 'arionum') !== 0;
    }

    /**
     * @param string $password
     */
    public function decrypt(string $password): void
    {
        $decodedData = base64_decode($this->rawData);
        $iv = substr($decodedData, 0, 16);
        $enc = substr($decodedData, 16);
        $hashedPassword = substr(hash('sha256', $password, true), 0, 32);
        $decrypted = openssl_decrypt(base64_decode($enc), 'aes-256-cbc', $hashedPassword, OPENSSL_RAW_DATA, $iv);

        if (strpos($decrypted, 'arionum') === 0) {
            $this->rawData = $decrypted;
        }
    }

    /**
     * @param string      $password
     * @param string|null $walletRaw
     * @return string
     * @throws \Exception
     */
    public function encrypt(string $password, string $walletRaw = null): string
    {
        if (!$walletRaw) {
            $walletRaw = 'arionum:'.$this->getPrivateKey().':'.$this->getPublicKey();
        }

        $passwordHashed = substr(hash('sha256', $password, true), 0, 32);
        $iv = random_bytes(16);

        $walletEncrypted = base64_encode(
            $iv.
            base64_encode(
                openssl_encrypt(
                    $walletRaw,
                    'aes-256-cbc',
                    $passwordHashed,
                    OPENSSL_RAW_DATA,
                    $iv
                )
            )
        );

        return $walletEncrypted;
    }

    /**
     *
     * @throws \Exception
     */
    public function decode(): void
    {
        if (!$this->isEncrypted()) {
            $decoded = explode(":", $this->rawData);

            $this->publicKey = $decoded[2];
            $this->privateKey = $decoded[1];
            $this->address = $this->getAddressFromPublicKey();
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getAddressFromPublicKey(): string
    {
        $hash = $this->publicKey;

        for ($i = 0; $i < 9; $i++) {
            $hash = hash('sha512', $hash, true);
        }

        return (new Base58())->encode($hash);
    }

    /**
     * @param string $address
     * @return bool
     */
    public function validAddress(string $address = null): bool
    {
        $address = $address ?? $this->address;

        return preg_match('/^[a-z0-9]+$/i', $address);
    }

    /**
     * @param float       $value
     * @param float       $fee
     * @param string      $address
     * @param string|null $message
     * @param int         $date
     * @param int         $version
     * @return string
     */
    public function generateSignature(
        $value,
        $fee,
        $address,
        $message,
        $date,
        int $version = 1
    ): string {
        return $value
            ."-"
            .$fee
            ."-"
            .$address
            ."-"
            .$message
            ."-"
            .$version
            ."-"
            .$this->publicKey
            ."-"
            .$date;
    }

    /**
     * @param float $value
     * @return float|int
     */
    public function getFee(float $value)
    {
        $fee = $value * 0.0025;

        if ($fee < 0.00000001) {
            $fee = 0.00000001;
        }

        if ($fee > 10) {
            $fee = 10;
        }

        return $fee;
    }

    /**
     * @param mixed  $data
     * @param string $privateKey
     * @return string
     * @throws \Exception
     */
    public function sign($data, string $privateKey): string
    {
        $private_key = $this->coin2pem($privateKey, true);

        $pkey = openssl_pkey_get_private($private_key);

        openssl_sign($data, $signature, $pkey, OPENSSL_ALGO_SHA256);

        return (new Base58())->encode($signature);
    }

    /**
     * @param mixed $data
     * @param bool  $isPrivateKey
     * @return string
     * @throws \Exception
     */
    public function coin2pem($data, $isPrivateKey = false): string
    {
        $data = (new Base58())->decode($data);

        $data = base64_encode($data);
        $dat = str_split($data, 64);
        $data = implode("\n", $dat);

        if ($isPrivateKey) {
            return "-----BEGIN EC PRIVATE KEY-----\n".$data."\n-----END EC PRIVATE KEY-----\n";
        }

        return "-----BEGIN PUBLIC KEY-----\n".$data."\n-----END PUBLIC KEY-----\n";
    }

    /**
     * @param string $data
     * @return string
     * @throws \Exception
     */
    public function pem2coin(string $data): string
    {
        $data = str_replace(array(
            '-----BEGIN PUBLIC KEY-----',
            '-----END PUBLIC KEY-----',
            '-----BEGIN EC PRIVATE KEY-----',
            '-----END EC PRIVATE KEY-----',
            "\n"
        ), '', $data);

        $data = base64_decode($data);

        return (new Base58)->encode($data);
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
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
     * @return bool
     */
    public function exists(): bool
    {
        return $this->exists;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $rawData
     * @return bool|int
     */
    public function saveRaw(string $rawData)
    {
        return file_put_contents($this->path, $rawData);
    }
}
