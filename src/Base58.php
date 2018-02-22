<?php

namespace Arionum\LightWalletCLI;

/**
 * Class Base58
 *
 * @author Mika Tuupola
 * @link   https://github.com/tuupola/base58
 */
class Base58
{
    /**
     * @param array $source
     * @param       $source_base
     * @param       $target_base
     * @return array
     */
    public function convert(array $source, $source_base, $target_base)
    {
        $result = [];
        while ($count = count($source)) {
            $quotient = [];
            $remainder = 0;
            for ($i = 0; $i !== $count; $i++) {
                $accumulator = $source[$i] + $remainder * $source_base;
                $digit = (integer)($accumulator / $target_base);
                $remainder = $accumulator % $target_base;
                if (count($quotient) || $digit) {
                    array_push($quotient, $digit);
                };
            }
            array_unshift($result, $remainder);
            $source = $quotient;
        }
        return $result;
    }

    /**
     * @param $data
     * @return string
     */
    public function encode($data)
    {
        if (is_integer($data)) {
            $data = [$data];
        } else {
            $data = str_split($data);
            $data = array_map(function ($character) {
                return ord($character);
            }, $data);
        }


        $converted = $this->convert($data, 256, 58);

        return implode("", array_map(function ($index) {
            $chars = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";
            return $chars[$index];
        }, $converted));
    }

    /**
     * @param      $data
     * @param bool $integer
     * @return int|string
     */
    public function decode($data, $integer = false)
    {
        $data = str_split($data);
        $data = array_map(function ($character) {
            $chars = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";
            return strpos($chars, $character);
        }, $data);
        /* Return as integer when requested. */
        if ($integer) {
            $converted = $this->convert($data, 58, 10);
            return (integer)implode("", $converted);
        }
        $converted = $this->convert($data, 58, 256);
        return implode("", array_map(function ($ascii) {
            return chr($ascii);
        }, $converted));
    }
}
