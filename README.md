# laravel-xlog
Extended Laravel Log Component
XLog adds `User ID`, `User IP`, `Track ID` to each log

## Installation

```bash
composer require php-monsters/laravel-xlog
```

Add this to your app service providers (only for Laravael < 5.5):
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

XLog::debug('test message');
XLog::info('test message');
XLog::notice('test message');
XLog::warning('test message');
XLog::error('test message');
XLog::critical('test message');
XLog::alert('test message');
XLog::emergency('test message');
```

## Pass parameters
```php

// passing string
$string = 'test'
XLog::info('test message', [$string]);

// passing array
$array = [1,2,'test',4.2];
XLog::info('test message', $array);

```


## Log exception

```php
XLog::exception($e, 'error');
XLog::exception($e, 'emergency');
```
