<?php declare(strict_types=1);

namespace Olifanton\Utils;

use ajf\TypedArrays\Uint8Array;

final class KeyPair
{
    public function __construct(
        public readonly Uint8Array $publicKey,
        public readonly Uint8Array $secretKey,
    )
    {
    }
}
