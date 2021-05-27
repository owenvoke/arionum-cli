# Arionum CLI

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Style CI][ico-styleci]][link-styleci]
[![Code Coverage][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A PHP-based command line wallet for Arionum.

## Install

Via Composer

```bash
$ composer global require owenvoke/arionum-cli
```

Via Phive

```bash
$ phive install owenvoke/arionum-cli
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
arionum transaction 'id'
```

Send a transaction (with an optional message):

```bash
arionum send 'address' 'value' [message]
```

#### Alias

Set your account alias:

```bash
arionum alias:set 'alias'
```

Send a transaction to an alias (with an optional message):

```bash
arionum alias:send 'alias' 'value' [message]
```

#### Masternode

Send a masternode announcement transaction:

```bash
arionum masternode:create 'ip'
```

Pause a specific masternode:

```bash
arionum masternode:pause
```

Resume the masternode mining:

```bash
arionum masternode:resume
```

Close a masternode and return the funds:

```bash
arionum masternode:release
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

```bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) and [CODE_OF_CONDUCT](.github/CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email security@voke.dev instead of using the issue tracker.

## Credits

- [Owen Voke][link-author]
- [arionum][link-arionum]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

Original code by [@arionum][link-arionum].

[ico-version]: https://img.shields.io/packagist/v/owenvoke/arionum-cli.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/owenvoke/arionum-cli/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/122668084/shield
[ico-code-quality]: https://img.shields.io/codecov/c/github/owenvoke/arionum-cli.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/owenvoke/arionum-cli.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/owenvoke/arionum-cli
[link-travis]: https://travis-ci.org/owenvoke/arionum-cli
[link-styleci]: https://styleci.io/repos/122668084
[link-code-quality]: https://codecov.io/gh/owenvoke/arionum-cli
[link-downloads]: https://packagist.org/packages/owenvoke/arionum-cli
[link-author]: https://github.com/owenvoke
[link-arionum]: https://github.com/arionum
[link-contributors]: ../../contributors
