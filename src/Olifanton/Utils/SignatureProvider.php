<?php declare(strict_types=1);

namespace Olifanton\Utils;

use ajf\TypedArrays\Uint8Array;
use Olifanton\Utils\Exceptions\CryptoException;

interface SignatureProvider
{
    /**
     * @throws CryptoException
     */
    public function signDetached(Uint8Array $message, Uint8Array $secretKey): Uint8Array;
}
