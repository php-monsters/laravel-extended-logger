<?php

namespace PhpMonsters\Log\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class XLog
 * @method debug(string $message, array $context = [])
 * @method info(string $message, array $context = [])
 * @method notice(string $message, array $context = [])
 * @method warning(string $message, array $context = [])
 * @method error(string $message, array $context = [])
 * @method critical(string $message, array $context = [])
 * @method alert(string $message, array $context = [])
 * @method emergency(string $message, array $context = [])
 * @method exception(Exception $e, bool $trace = false, string $name = 'error')
 * @package PhpMonsters\XLog
 * @author  Aboozar Ghaffari <aboozar.ghf@gmail.com>
 */
class XLog extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor ()
    {
        return 'PhpMonsters\Log\Logger';
    }
}
