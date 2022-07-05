<?php declare(strict_types=1);

namespace Olifanton\Utils\Tests;

use ajf\TypedArrays\Uint8Array;
use Olifanton\Utils\Bytes;
use Olifanton\Utils\Checksum;
use PHPUnit\Framework\TestCase;

class ChecksumTest extends TestCase
{
    public function testCrc16(): void
    {
        $stub = new Uint8Array([1, 2, 3]);
        $this->assertEquals(
            "6131",
            Bytes::bytesToHexString(Checksum::crc16($stub)),
        );

        $stub = new Uint8Array([3, 2, 1]);
        $this->assertEquals(
            "2f13",
            Bytes::bytesToHexString(Checksum::crc16($stub)),
        );

        $stub = new Uint8Array([]);
        $this->assertEquals(
            "0000",
            Bytes::bytesToHexString(Checksum::crc16($stub)),
        );
    }

    public function testCrc32c(): void
    {
        $stub = new Uint8Array([1, 2, 3]);
        $this->assertEquals(
            "1ef230f1",
            Bytes::bytesToHexString(Checksum::crc32c($stub)),
        );

        $stub = new Uint8Array([3, 2, 1]);
        $this->assertEquals(
            "e4d0645f",
            Bytes::bytesToHexString(Checksum::crc32c($stub)),
        );

        $stub = new Uint8Array([]);
        $this->assertEquals(
            "00000000",
            Bytes::bytesToHexString(Checksum::crc32c($stub)),
        );
    }
}
