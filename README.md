# arionum-cli

A php based cli wallet for Arionum.

## Install

Via Composer

```bash
$ composer require pxgamer/arionum-cli
```

## Usage

```bash
arionum [command]
```

### Available Commands

Print the balance:

```bash
arionum balance
```

Print the wallet's data:

```bash
arionum export
```

Display data about the current block:

```bash
arionum block
```

Encrypt the wallet:

```bash
arionum encrypt
```

Decrypt the wallet:

```bash
arionum decrypt
```

Display the latest transactions:

```bash
arionum transactions
```

Display data about a specific transaction:

```bash
arionum transaction [id]
```

Send a transaction (with an optional message):

```bash
arionum send [address] [value] [message]
```

## Testing

```bash
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
