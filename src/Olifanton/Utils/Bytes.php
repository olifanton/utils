<?php declare(strict_types=1);

namespace Olifanton\Utils;

use ajf\TypedArrays\ArrayBuffer;
use ajf\TypedArrays\Uint16Array;
use ajf\TypedArrays\Uint32Array;
use ajf\TypedArrays\Uint8Array;
use InvalidArgumentException;
use Olifanton\Utils\Helpers\AjfByteReader;

class Bytes
{
    public static final function readNBytesUIntFromArray(int $n, Uint8Array $uint8Array): int
    {
        $res = 0;

        for ($i = 0; $i < $n; $i++) {
            $res *= 256;
            $res += $uint8Array[$i];
        }

        return $res;
    }

    public static final function compareBytes(Uint8Array $a, Uint8Array $b): bool
    {
        return self::arrayToBytes($a) === self::arrayToBytes($b); // @TODO: RAM using optimization
    }

    public static final function arraySlice(Uint8Array $bytes, int $start, int $end): Uint8Array
    {
        $result = new Uint8Array($end - $start);
        $j = 0;

        for ($i = $start; $i < $end; $i++) {
            $result[$j] = $bytes[$i];
            $j++;
        }

        return $result;
    }

    public static final function concatBytes(Uint8Array $a, Uint8Array $b): Uint8Array
    {
        $c = new Uint8Array($a->length + $b->length);
        $i = 0;

        for ($j = 0; $j < $a->length; $j++) {
            $c[$i] = $a[$j];
            $i++;
        }

        for ($j = 0; $j < $b->length; $j++) {
            $c[$i] = $b[$j];
            $i++;
        }

        return $c;
    }

    public static final function stringToBytes(string $str, int $size = 1): Uint8Array
    {
        $buf = null;
        $bufView = null;

        if ($size === 1) {
            $buf = new ArrayBuffer(strlen($str));
            $bufView = new Uint8Array($buf);
        }

        if ($size === 2) {
            $buf = new ArrayBuffer(strlen($str) * 2);
            $bufView = new Uint16Array($buf);
        }

        if ($size === 4) {
            $buf = new ArrayBuffer(strlen($str) * 4);
            $bufView = new Uint32Array($buf);
        }

        if ($buf === null) {
            throw new InvalidArgumentException("Unsupported size: ${size}");
        }

        for ($i = 0, $strLen = strlen($str); $i < $strLen; $i++) {
            $bufView[$i] = ord($str[$i]);
        }

        return new Uint8Array($bufView);
    }

    public static final function hexStringToBytes(string $str): Uint8Array
    {
        $str = mb_strtolower($str);
        $length2 = strlen($str);

        if ($length2 % 2 !== 0) {
            throw new InvalidArgumentException("Hex string must have length a multiple of 2");
        }

        $length = $length2 / 2;
        $result = new Uint8Array($length);

        for ($i = 0; $i < $length; $i++) {
            $b = substr($str, $i * 2, 2);
            $result[$i] = hexdec($b);
        }

        return $result;
    }

    public static final function bytesToHexString(Uint8Array $bytes): string
    {
        $result = [];

        for ($i = 0; $i < $bytes->length; $i++) {
            $result[] = str_pad(dechex($bytes[$i]), 2, "0", STR_PAD_LEFT);
        }

        return implode("", $result);
    }

    public static final function bytesToArray(string $bytes): Uint8Array
    {
        $arr = new Uint8Array(strlen($bytes));

        foreach (str_split($bytes) as $i => $byte) {
            $arr->offsetSet($i, unpack("C", $byte)[1]);
        }

        return $arr;
    }

    public static final function arrayToBytes(Uint8Array $arr): string
    {
        return AjfByteReader::getBytes($arr->buffer);
    }

    public static function bytesToBase64(Uint8Array $bytes): string
    {
        return base64_encode(AjfByteReader::getBytes($bytes->buffer));
    }

    public static final function base64ToBytes(string $base64): Uint8Array
    {
        $binaryString = base64_decode($base64);
        $length = strlen($binaryString);
        $bytes = new Uint8Array($length);

        for ($i = 0; $i < $length; $i++) {
            $bytes[$i] = ord($binaryString[$i]);
        }

        return $bytes;
    }
}
