<?php

namespace pxgamer\Arionum;

use StephenHill\Base58;

/**
 * Class Wallet
 */
class Wallet
{
    /**
     * The default wallet file name.
     */
    const WALLET_NAME = 'wallet.aro';

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
        $this->path = realpath($path);
        $this->exists = file_exists($this->path);

        if ($this->exists) {
            $this->rawData = file_get_contents($this->path);
        }
    }

    /**
     * @return bool
     */
    public function isEncrypted()
    {
        return substr($this->rawData, 0, 7) !== 'arionum';
    }

    /**
     * @param string $password
     */
    public function decrypt(string $password)
    {
        $decodedData = base64_decode($this->rawData);
        $iv = substr($decodedData, 0, 16);
        $enc = substr($decodedData, 16);
        $hashedPassword = substr(hash('sha256', $password, true), 0, 32);
        $decrypted = openssl_decrypt(base64_decode($enc), 'aes-256-cbc', $hashedPassword, OPENSSL_RAW_DATA, $iv);

        if (substr($decrypted, 0, 7) == 'arionum') {
            $this->rawData = $decrypted;
        }
    }

    /**
     * @param string $password
     * @return string
     * @throws \Exception
     */
    public function encrypt(string $password)
    {
        $walletRaw = 'arionum:'.$this->getPrivateKey().':'.$this->getPublicKey();

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
     */
    public function decode()
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
    private function getAddressFromPublicKey()
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
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return $this->exists;
    }

    /**
     * @return string
     */
    public function getPath()
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
