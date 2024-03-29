<?php declare(strict_types=1);

namespace Olifanton\Utils;

use ajf\TypedArrays\Uint8Array;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;

class Address implements \Stringable
{
    private const BOUNCEABLE_TAG = 0x11;
    private const NON_BOUNCEABLE_TAG = 0x51;
    private const TEST_FLAG = 0x80;

    private int $wc;

    private Uint8Array $hashPart;

    private bool $isTestOnly;

    private bool $isUserFriendly;

    private bool $isBounceable;

    private bool $isUrlSafe;

    public function __construct(string | Address $anyForm)
    {
        if ($anyForm instanceof Address) {
            $this->wc = $anyForm->wc;
            $this->hashPart = $anyForm->getHashPart();
            $this->isTestOnly = $anyForm->isTestOnly;
            $this->isUserFriendly = $anyForm->isUserFriendly;
            $this->isBounceable = $anyForm->isBounceable;
            $this->isUrlSafe = $anyForm->isUrlSafe;
            return;
        }

        if (strpos($anyForm, "-") > 0 || strpos($anyForm, "_") > 0) {
            $this->isUrlSafe = true;
            $anyForm = str_replace(["-", "_"], ["+", '/'], $anyForm);
        } else {
            $this->isUrlSafe = false;
        }

        if (str_contains($anyForm, ":")) {
            $chunks = explode(":", $anyForm);

            if (count($chunks) !== 2) {
                throw new InvalidArgumentException("Invalid address: " . $anyForm);
            }

            $wc = (int)$chunks[0];

            if ($wc !== 0 && $wc !== -1) {
                throw new InvalidArgumentException('Invalid address wc: ' . $anyForm);
            }

            $hex = $chunks[1];

            if (strlen($hex) !== 64) {
                throw new InvalidArgumentException("Invalid address hex: " . $anyForm);
            }

            $this->isUserFriendly = false;
            $this->wc = $wc;
            $this->hashPart = Bytes::hexStringToBytes($hex);
            $this->isTestOnly = false;
            $this->isBounceable = false;
        } else {
            $parseResult = self::parseFriendlyAddress($anyForm);

            $this->isUserFriendly = true;
            $this->wc = $parseResult['workchain'];
            $this->hashPart = $parseResult['hashPart'];
            $this->isTestOnly = $parseResult['isTestOnly'];
            $this->isBounceable = $parseResult['isBounceable'];
        }
    }

    public function toString(?bool $isUserFriendly = null,
                             ?bool $isUrlSafe = null,
                             ?bool $isBounceable = null,
                             ?bool $isTestOnly = null): string
    {
        $isUserFriendly = ($isUserFriendly === null) ? $this->isUserFriendly : $isUserFriendly;
        $isUrlSafe = ($isUrlSafe === null) ? $this->isUrlSafe : $isUrlSafe;
        $isBounceable = ($isBounceable === null) ? $this->isBounceable : $isBounceable;
        $isTestOnly = ($isTestOnly === null) ? $this->isTestOnly : $isTestOnly;

        if (!$isUserFriendly) {
            return $this->wc . ":" . Bytes::bytesToHexString($this->hashPart);
        }

        $tag = $isBounceable ? self::BOUNCEABLE_TAG : self::NON_BOUNCEABLE_TAG;

        if ($isTestOnly) {
            $tag |= self::TEST_FLAG;
        }

        $addr = new Uint8Array(34);
        $addr[0] = $tag;
        $addr[1] = $this->wc;
        $addr->set($this->hashPart, 2);

        $addressWithChecksum = new Uint8Array(36);
        $addressWithChecksum->set($addr);
        $addressWithChecksum->set(Checksum::crc16($addr), 34);
        $addressBase64 = base64_encode(Bytes::arrayToBytes($addressWithChecksum));

        if ($isUrlSafe) {
            $addressBase64 = str_replace(['+', '/'], ["-", '_'], $addressBase64);
        }

        return $addressBase64;
    }

    public function getWorkchain(): int
    {
        return $this->wc;
    }

    public function getHashPart(): Uint8Array
    {
        return Bytes::arraySlice($this->hashPart, 0, 32);
    }

    public function isTestOnly(): bool
    {
        return $this->isTestOnly;
    }

    public function isUserFriendly(): bool
    {
        return $this->isUserFriendly;
    }

    public function isBounceable(): bool
    {
        return $this->isBounceable;
    }

    public function isUrlSafe(): bool
    {
        return $this->isUrlSafe;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public static function isValid(string | Address $address): bool
    {
        try {
            new Address($address);

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    #[ArrayShape([
        'isTestOnly' => "bool",
        'isBounceable' => "bool",
        'workchain' => "int",
        'hashPart' => "ajf\\TypedArrays\\Uint8Array",
    ])]
    private static function parseFriendlyAddress(string $addressString): array
    {
        if (strlen($addressString) !== 48) {
            throw new InvalidArgumentException("User-friendly address should contain strictly 48 characters");
        }

        $data = Bytes::stringToBytes(base64_decode($addressString));

        if ($data->length !== 36) {
            throw new InvalidArgumentException("Unknown address type: byte length is not equal to 36");
        }

        $addr = Bytes::arraySlice($data, 0, 34);
        $crc = Bytes::arraySlice($data, 34, 36);
        $checkCrc = Checksum::crc16($addr);

        if (!Bytes::compareBytes($crc, $checkCrc)) {
            throw new InvalidArgumentException("Address CRC16-checksum error");
        }

        $tag = $addr[0];
        $isTestOnly = false;

        if ($tag & self::TEST_FLAG) {
            $isTestOnly = true;
            $tag ^= self::TEST_FLAG;
        }

        if (($tag !== self::BOUNCEABLE_TAG) && ($tag !== self::NON_BOUNCEABLE_TAG)) {
            throw new InvalidArgumentException("Unknown address tag");
        }

        $isBounceable = $tag === self::BOUNCEABLE_TAG;

        if ($addr[1] === 0xff) {
            $workchain = -1;
        } else {
            $workchain = $addr[1];
        }

        if ($workchain !== 0 && $workchain !== -1) {
            throw new InvalidArgumentException("Invalid address workchain: " . $workchain);
        }

        $hashPart = Bytes::arraySlice($addr, 2, 34);

        return [
            'isTestOnly' => $isTestOnly,
            'isBounceable' => $isBounceable,
            'workchain' => $workchain,
            'hashPart' => $hashPart,
        ];
    }
}
