# laravel-xlog
Extended Laravel Log Component
XLog adds `User ID`, `User IP`, and `unique Track ID` to each log in a PHP lifecycle.
So you can fetch all logs that are submitted through a Laravel request lifecycle (from beginning to end).

You can fetch all logs using the tail command. For example if your unique track id was `523586093e` then you can use it like below:

```
cat laravel.log | grep 523586093e 
```
output:

```
[2024-06-26 00:36:12] production.DEBUG: AsanpardakhtController::token_request {"servicetypeid":1,"merchantconfigurationid":38718596,"localinvoiceid":17193495725213,"amountinrials":6980000,"localdate":"20240626 003612","callbackurl":"https://shop.test/payment/gateway-transactions/pqj5nm/callback","paymentid":0,"additionaldata":null,"settlementportions":[{"iban":"IR600780100710844707074710","amountInRials":6980000,"paymentId":0}],"sid":"","uip":"31.14.119.2","uid":"user","xTrackId":"523586093e"}
[2024-06-26 00:36:13] production.INFO: AsanpardakhtController::token_response hd764QZna1fl1ALEwjoA: 200 {"sid":"","uip":"31.14.119.2","uid":"user","xTrackId":"523586093e"}
```
## Installation

```bash
composer require php-monsters/laravel-xlog
```

Add this to your app service providers (only for Laravel < 5.5):
```php
    PhpMonsters\Log\XLogServiceProvider::class,
```

## Config (optional)
add following keys to your project .env file

```ini
# track id key
XLOG_ADD_USERID= (default true)
XLOG_TRACK_ID_KEY= (default xTrackId)
```


## Usage

```php
use PhpMonsters\Log\XLog; // or register XLog Facade

XLog::debug('my log message');
XLog::info('my log message');
XLog::notice('my log message');
XLog::warning('my log message');
XLog::error('my log message');
XLog::critical('my log message');
XLog::alert('my log message');
XLog::emergency('my log message');
```

## Pass parameters
```php

// passing string as the second parameter
$string = 'test'
XLog::info('log message', [$string]);

// passing array
$array = ['a' => 1, 'b' => 2, 'c' => 'value3', 'd' => 4.2];
XLog::info('log message', $array);
// log input data in the controller's action
XLog::info('verify transaction request', $request->all());
```


## Log exception

```php
//The first parameter is the thrown exception. second parameter is the level of the log.
XLog::exception($exception, 'error');
XLog::exception($e, 'emergency');
```
