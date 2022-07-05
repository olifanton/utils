<?php declare(strict_types=1);

namespace Olifanton\Utils;

use ajf\TypedArrays\Uint8Array;
use Olifanton\Utils\Exceptions\CryptoException;

interface DigestProvider
{
    /**
     * @throws CryptoException
     */
    public function digestSha256(Uint8Array $bytes): Uint8Array;
}
