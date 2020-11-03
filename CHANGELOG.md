# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.4.5] - 2020-11-03
### Added

- Menux method slotMachine() (slots button)
- Added new event onSlotMachine
- Dice method isSlotMachine (check dice type)
- Dice method getSlots

## [1.4.0] - 2020-04-15
### Added

- Menux method scene(string $text, string|Scene $scene) (Working only with Menux::middleware())
- Stage method addScenes(...$scenes) - for multiple scene binding
- Context method hasVar($variable) - check optional template variable.
- Added reflection for template variables.

### Fixed

- IntegerValidator, FloatValidator - value casting
- Context method var (fixed default value type (string to mixed))

## [1.3.4 - 1.3.6] - 2020-04-13
### Fixed

- Fixed Validator

## [1.3.3] - 2020-04-09
### Fixed

- Fixed ContextContainer

## [1.3.2] - 2020-04-09
### Added

- Guzzle Client options array (Settings::setClientOptions(array))

### Fixed

- Proxy

## [1.3.1] - 2020-04-07
### Added

- Settings method: setLogger(LoggerInterface) (by PSR)

### Fixed

- Menux property bug

## [1.3.0] - 2020-04-06
### Added

- Context Container (DI + Service Container)
- Default property "resize_keyboard" for Menux
- Multiple fields ("|" separated) for onMessage, onUpdate events.

### Fixed

- Menux property bug


## [1.2.0] - 2020-03-30
### Added

- Telegram bot API v4.7 support
- Entity method getfield(fieldname, default)
- Entity method export() - returns entity as array
- Context methods: replyDice, getDice, getDiceValue, poll, pollAnswer
