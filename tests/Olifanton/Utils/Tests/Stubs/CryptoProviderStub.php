<?php declare(strict_types=1);

namespace Olifanton\Utils\Tests\Stubs;

use ajf\TypedArrays\Uint8Array;
use Olifanton\Utils\DigestProvider;
use Olifanton\Utils\KeyPair;
use Olifanton\Utils\KeyPairProvider;

class CryptoProviderStub implements DigestProvider, KeyPairProvider
{
    public function digestSha256(Uint8Array $bytes): Uint8Array
    {
        return new Uint8Array(array_fill(0, 32, 0));
    }

    public function keyPairFromSeed(Uint8Array $seed): KeyPair
    {
        return new KeyPair(
            new Uint8Array(array_fill(0, 32, 0)),
            new Uint8Array(array_fill(0, 64, 0)),
        );
    }

    public function newKeyPair(): KeyPair
    {
        return new KeyPair(
            new Uint8Array(array_fill(0, 32, 0)),
            new Uint8Array(array_fill(0, 64, 0)),
        );
    }

    public function newSeed(): Uint8Array
    {
        return new Uint8Array(array_fill(0, 32, 0));
    }
}
