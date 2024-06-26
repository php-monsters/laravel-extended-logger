# laravel-xlog
Extended Laravel Log Component
XLog adds `User ID`, `User IP`, and `unique Track ID` to each log

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
