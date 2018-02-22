# light-wallet-cli

A php based cli wallet for Arionum.

Requires php 7.1

## Install

Via Composer

```bash
$ composer require arionum/light-arionum-cli
```

## Usage

```bash
light-arionum <command> <options>
```

### Available Commands

Print the balance:

```bash
arionum balance <options>
```

Print the wallet's data:

```bash
arionum export <options>
```

Display data about the current block:

```bash
arionum block <options>
```

Encrypt the wallet:

```bash
arionum encrypt <options>
```

Decrypt the wallet:

```bash
arionum decrypt <options>
```

Display the latest transactions:

```bash
arionum transactions <options>
```

Display data about a specific transaction:

```bash
arionum transaction [id] <options>
```

Send a transaction (with an optional message):

```bash
arionum send [address] [value] [message] <options>
```

## Testing

```bash
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Development Funds

ARO: 5WuRMXGM7Pf8NqEArVz1NxgSBptkimSpvuSaYC79g1yo3RDQc8TjVtGH5chQWQV7CHbJEuq9DmW5fbm CEW4AghQr

LTC: LWgqzbXGeucKaMmJEvwaAWPFrAgKiJ4Y4m

BTC: 1LdoMmYitb4C3pXoGNLL1VRj7xk3smGXoU

ETH: 0x4B904bDf071E9b98441d25316c824D7b7E447527

BCH: qrtkqrl3mxzdzl66nchkgdv73uu3rf7jdy7el2vduw

If you'd like to support the Arionum development, you can donate to the addresses listed above.
