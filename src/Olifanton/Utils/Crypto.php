<?php declare(strict_types=1);

namespace Olifanton\Utils;

use ajf\TypedArrays\Uint8Array;
use Olifanton\Utils\CryptoProviders\DefaultProvider;
use Olifanton\Utils\Exceptions\CryptoException;

class Crypto
{
    private static ?KeyPairProvider $keyPairProvider = null;

    private static ?DigestProvider $digestProvider = null;

    public static final function setKeyPairProvider(KeyPairProvider $keyPairProvider): void
    {
        self::$keyPairProvider = $keyPairProvider;
    }

    public static final function setDigestProvider(DigestProvider $digestProvider): void
    {
        self::$digestProvider = $digestProvider;
    }

    /**
     * @throws CryptoException
     */
    public static final function sha256(Uint8Array $bytes): Uint8Array
    {
        return self::ensureDigestProvider()->digestSha256($bytes);
    }

    /**
     * @throws CryptoException
     */
    public static final function keyPairFromSeed(Uint8Array $seed): KeyPair
    {
        return self::ensureKeyPairProvider()->keyPairFromSeed($seed);
    }

    /**
     * @throws CryptoException
     */
    public static final function newKeyPair(): KeyPair
    {
        return self::ensureKeyPairProvider()->newKeyPair();
    }

    /**
     * @throws CryptoException
     */
    public static final function newSeed(): Uint8Array
    {
        return self::ensureKeyPairProvider()->newSeed();
    }

    private static function ensureKeyPairProvider(): KeyPairProvider
    {
        if (!self::$keyPairProvider) {
            self::$keyPairProvider = new DefaultProvider();
        }

        return self::$keyPairProvider;
    }

    private static function ensureDigestProvider(): DigestProvider
    {
        if (!self::$digestProvider) {
            self::$digestProvider = new DefaultProvider();
        }

        return self::$digestProvider;
    }
}
