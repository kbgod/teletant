# Teletant Framework
###### Authors: [@askoldex](https://t.me/monarkhov), [@uniqkic](https://t.me/uniqkic)
###### Inspiration: [telegraf](https://github.com/telegraf/telegraf), [irazasyed/telegram-bot-sdk](https://github.com/irazasyed/telegram-bot-sdk)

## Examples

[Click](https://github.com/askoldex/teletant-examples)

## Getting Started
#### Requirements
1. PHP 7.2
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

#### Run as webhook (listen webhook address)
```php
$bot->listen();
```

#### Fast Examples:
##### Making command
```php
$bot->onCommand('start', function (Context $ctx) {
    $ctx->reply('Hello world');
});
```

##### [Message](https://core.telegram.org/bots/api#message) field handler
```php
$bot->onMessage('sticker', function (Context $ctx) {
    $ctx->reply('Nice sticker!');
});
```

##### [Update](https://core.telegram.org/bots/api#update) field handler
```php
$bot->onUpdate('message', function (Context $ctx) {
    $ctx->reply('Answer on any message (text, sticker, photo, etc.)');
});
```

##### [CallbackQuery](https://core.telegram.org/bots/api#callbackquery) "data" field handler
```php
$bot->onAction('like', function (Context $ctx) {
    $ctx->reply('You pressed the button with callaback_data=like');
});
```

##### Find substring in Message text
```php
$bot->onHears('fu*k', function (Context $ctx) {
    $ctx->reply('Stop! If you continue, you will be banned');
});
```
***Or use array substrings***
```php
$bot->onHears(['di*k', 'f**k'], function (Context $ctx) {
    $ctx->reply('Stop! If you continue, you will be banned');
});
```



##### Making command with parameters
```php
$bot->onText('/message {user:integer} {message:string}', function (Context $ctx) {
    $ctx->withVars()->reply("User id: {v-user}\nMessage: {v-message}");
});
```
> Parameter without validation type syntax: {name}\
> Optional parameter without validation type syntax: {name?}\
> Parameter syntax: {name:validator_name}\
> Optional parameter syntax: {name:validation_name?}\
>
> If you need >2 spaced parameters. For example:\
> /msg {a:string} {b:string}. If message text will be "/msg hello world guys". Variable values will be: a = hello world, b = guys).\
>To explicitly separate variables you need to use Boxed variables:\
> Syntax: {name:string:box}\
> Example: /msg {name:string:"} {msg:string:"}\
> In result you getcommand with syntax:\
> /msg "{name:string}" "{msg:string}"\
> Example: /msg "John Smith" "hello world"\
> Variables: name=John Smith, msg=hello world.\
> Boxed variable may be optional, syntax: {name:type:box?}

***Default validation types***

| Validator  | Pattern |
| ------------- | ------------- |
| integer  | [\d]+  |
| float  | -?\d+(\.\d+)?  |
| string  | [\w\s]+  |
| word  | [\w]+ |
| char  | [\w] |
| any  | (.*?) |

> Validator "any" used as default ({name} == {name:any})

| Event  | Argument supporting |
| ------------- | ------------- |
| onStart  | NO  |
| onPoll  | NO  |
| onPollAnswer  | NO  |
| onDice  | NO  |
| onText  | YES  |
| onAction  | YES |
| onHears  | YES  |
| onCommand  | NO|
| onMessage  | NO |
| onUpdate  | NO |
| onInlineQuery  | YES |
