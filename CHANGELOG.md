# Changelog

All notable changes to `arionum-cli` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](https://keepachangelog.com) principles.

## [Unreleased]

### Removed
- Remove the `miner`/`mine` commands ([#12](https://github.com/pxgamer/arionum-cli/issues/12))

## [v1.5.1] - 2018-11-08

### Fixed
- Fix a typo in the masternode commands ([7eb699e7](https://github.com/pxgamer/arionum-cli/commit/7eb699e791e678ed4f7fbeae9b349c51b7f542f5))

## [v1.5.0] - 2018-11-08

### Added
- Add support for exporting transactions to JSON, XML, CSV or table ([#6](https://github.com/pxgamer/arionum-cli/issues/6))

### Changed
- Update to use Guzzle for all API calls ([#4](https://github.com/pxgamer/arionum-cli/issues/4))
- Update the visibility of class constants ([38aa74ff](https://github.com/pxgamer/arionum-cli/commit/38aa74ff790123ee17c1feab05887ff6c354dc79))
- Optimise the imports for classes and functions
- Update the naming of the new unit tests ([692097ee](https://github.com/pxgamer/arionum-cli/commit/692097eead845dc12da95c9de85b9b7b131d1e9a))

### Deprecated
- Deprecate the `miner`/`mine` commands ([#12](https://github.com/pxgamer/arionum-cli/issues/12))

## [v1.4.1] - 2018-08-23

### Added
- Add support for `arionum/node` v0.4.2 and the latest hardfork ([2a509f4](https://github.com/pxgamer/arionum-cli/commit/2a509f4d593dacffe0ea5b70a24f972f9b68702f))

## [v1.4.0] - 2018-08-16

### Added
- Add support for custom peers ([#5](https://github.com/pxgamer/arionum-cli/issues/5))
- Add support for an address in the `transactions` command ([#7](https://github.com/pxgamer/arionum-cli/issues/7))

## [v1.3.0] - 2018-08-10

### Added
- Add support for masternode commands ([#3](https://github.com/pxgamer/arionum-cli/issues/3))

## [v1.2.0] - 2018-08-10

### Added
- Add support for alias commands ([#2](https://github.com/pxgamer/arionum-cli/issues/2))

## [v1.1.0] - 2018-08-06

### Added
- Add the PHP miner into the CLI ([bb61cc4](https://github.com/pxgamer/arionum-cli/commit/bb61cc4d2afa682f3b9b1eb6b222b1207b18bd5d))
- Add Phar releases during the Travis CI process ([#1](https://github.com/pxgamer/arionum-cli/issues/1))

### Changed
- Move community files to `.github` ([87c290a](https://github.com/pxgamer/arionum-cli/commit/87c290a2269aca36b761c6dcb57584ac65df263f))

### Removed
- Remove the `structure` section from the README ([f8d76ec](https://github.com/pxgamer/arionum-cli/commit/f8d76ece4f704e375ead9bbcff59f66b005cf046))
- Remove support for PHPUnit v6 ([e9b4969](https://github.com/pxgamer/arionum-cli/commit/e9b4969e14e3ade65d8d850e7b5ad597f9a1220c))

## v1.0.0 - 2018-02-23

### Added
- Complete restructure using Composer
- Add Box Phar support
- Add Symfony Console v4

[Unreleased]: https://github.com/pxgamer/arionum-cli/compare/master...develop
[v1.5.1]: https://github.com/pxgamer/arionum-cli/compare/v1.5.0...v1.5.1
[v1.5.0]: https://github.com/pxgamer/arionum-cli/compare/v1.4.1...v1.5.0
[v1.4.1]: https://github.com/pxgamer/arionum-cli/compare/v1.4.0...v1.4.1
[v1.4.0]: https://github.com/pxgamer/arionum-cli/compare/v1.3.0...v1.4.0
[v1.3.0]: https://github.com/pxgamer/arionum-cli/compare/v1.2.0...v1.3.0
[v1.2.0]: https://github.com/pxgamer/arionum-cli/compare/v1.1.0...v1.2.0
[v1.1.0]: https://github.com/pxgamer/arionum-cli/compare/v1.0.0...v1.1.0
