# arionum-cli

A php based cli wallet for Arionum.

## Install

Via Composer

```bash
$ composer require pxgamer/arionum-cli
```

## Usage

```bash
arionum <command> <options>
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
