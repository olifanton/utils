<?php declare(strict_types=1);

namespace Olifanton\Utils;

use ajf\TypedArrays\Uint8Array;
use Olifanton\Utils\Exceptions\CryptoException;

interface KeyPairProvider
{
    /**
     * @throws CryptoException
     */
    public function keyPairFromSeed(Uint8Array $seed): KeyPair;

    /**
     * @throws CryptoException
     */
    public function newKeyPair(): KeyPair;

    /**
     * @throws CryptoException
     */
    public function newSeed(): Uint8Array;
}
