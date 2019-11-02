# Teletant Framework
###### Authors: [@askoldex](https://t.me/monarkhov), [@uniqkic](https://t.me/uniqkic)
###### Inspiration: [telegraf](https://github.com/telegraf/telegraf), [irazasyed/telegram-bot-sdk](https://github.com/irazasyed/telegram-bot-sdk)

## Examples

[Click](https://github.com/askoldex/teletant-examples)

## Getting Started
#### Requirements
1. PHP 7.1
2. Composer

#### Installation
`composer require askoldex/teletant`

#### Usage
```php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use Askoldex\Teletant\Bot;
use Askoldex\Teletant\Context;
use Askoldex\Teletant\Settings;


$settings = new Settings('token');
$settings->setHookOnFirstRequest(false);
$bot = new Bot($settings);
```

#### Run as long poll (getUpdates)
```php
$bot->polling();
```

#### Run as webhook (list webhook address)
```php
$bot->listen();
```
