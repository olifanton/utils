Olifanton PHP utils library
---

![Code Coverage Badge](./.github/badges/coverage.svg)
![Tests](https://github.com/olifanton/utils/actions/workflows/tests.yml/badge.svg)
[![Latest Stable Version](https://poser.pugx.org/olifanton/utils/v/stable)](https://packagist.org/packages/olifanton/utils)
[![Total Downloads](https://poser.pugx.org/olifanton/utils/downloads)](https://packagist.org/packages/olifanton/utils)

PHP port of [`tonweb-utils`](https://github.com/toncenter/tonweb/tree/master/src/utils) JS library

## Installation

```bash
composer require olifanton/utils
```

## Documentation

### Getting started

Install [`olifanton/utils`](https://packagist.org/packages/olifanton/utils) package via Composer and include autoload script:

```php
<?php declare(strict_types=1);

require __DIR__ . "/vendor/autoload.php";

use Olifanton\Utils\Address;
use Olifanton\Utils\Bytes;
use Olifanton\Utils\Crypto;
use Olifanton\Utils\Units;

// Now you can use Olifanton utils classes

```

### Library classes

- [Address](https://github.com/olifanton/utils#olifantonutilsaddress)
- [Bytes](https://github.com/olifanton/utils#olifantonutilsbytes)
- [Crypto](https://github.com/olifanton/utils#olifantonutilscrypto)
- [Units](https://github.com/olifanton/utils#olifantonutilsunits)

---

#### `Olifanton\Utils\Address`

`Address` is a class that allows you to work with smart contract addresses in the TON network. Read more about Addresses in official [documentation](https://ton.org/docs/learn/overviews/addresses).

##### _Address_ constructor

```php
/**
 * @param string | \Olifanton\Utils\Address $anyForm
 */
public function __construct(string | Address $anyForm)
```

Parameters:

- `$anyForm` &mdash; Address in supported form. Supported values are:
    - Friendly format (base64 encoded, URL safe or not): `EQBvI0aFLnw2QbZgjMPCLRdtRHxhUyinQudg6sdiohIwg5jL`;
    - Raw form: `-1:fcb91a3a3816d0f7b8c2c76108b8a9bc5a6b7a55bd79f8ab101c52db29232260`;
    - Other `Address` instance, in this case the new instance will be an immutable copy of the other address.

Depending on the passed value, the Address instance will store information about the input address flags.

If the input value is not a valid address, then `\InvalidArgumentException` will be thrown.

##### _Address_ static methods

###### isValid(string | \Olifanton\Utils\Address $anyForm): bool
Checks if the passed value is a valid address in any form.

##### _Address_ methods

###### toString(): string
```php
/**
 * @param bool|null $isUserFriendly User-friendly flag
 * @param bool|null $isUrlSafe URL safe encoded flag
 * @param bool|null $isBounceable Bounceable address flag
 * @param bool|null $isTestOnly Testnet Only flag
 */
public function toString(?bool $isUserFriendly = null,
                         ?bool $isUrlSafe = null,
                         ?bool $isBounceable = null,
                         ?bool $isTestOnly = null): string
```
Returns a string representation of Address.

If all parameters are left as default, then the address will be formatted with the same flags whose value was recognized in the constructor.

###### getWorkchain(): int

###### getHashPart(): Uint8Array

###### isTestOnly(): bool

###### isBounceable(): bool

###### isUserFriendly(): bool

###### isUrlSafe(): bool

---

#### `Olifanton\Utils\Bytes`

##### _Bytes_ methods

`@TODO`

---

#### `Olifanton\Utils\Crypto`

##### _Crypto_ methods

`@TODO`

---

#### `Olifanton\Utils\Units`

##### _Units_ methods

`@TODO`

---

## Tests

```bash
composer run test
```

---

# License

MIT
