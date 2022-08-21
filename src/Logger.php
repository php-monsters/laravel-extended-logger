<?php

namespace PhpMonsters\Log;

use Exception;
use Illuminate\Support\Facades\Auth;

class Logger
{
    /**
     * @var array
     */
    private static $LOG_LEVELS = ['debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency'];

    /**
     * @var string
     */
    private static $userUserId = null;

    /**
     * @var bool
     */
    private static $firstCall = false;

    /**
     * @param Exception $e
     * @param bool $trace log trace string or not
     * @param string $name
     * @return mixed
     */
    public static function exception(Exception $e, bool $trace = false, string $name = 'error')
    {
        $arguments = [];
        $arguments [0] = 'exception-> ' . $e->getMessage();
        $arguments [1] = [
            'code' => $e->getCode(),
            'file' => basename($e->getFile()),
            'line' => $e->getLine(),
            self::getTrackIdKey() => self::getTrackId(),
        ];

        if ($trace) {
            $arguments[1]['trace'] = $e->getTraceAsString();
        }

        return self::__callStatic($name, $arguments);
    }

    /**
     * @return string
     */
    public static function getTrackIdKey(): string
    {
        return env('XLOG_TRACK_ID_KEY', 'xTrackId');
    }

    /**
     * @return string
     */
    protected static function getTrackId(): string
    {
        $trackIdKey = self::getTrackIdKey();

        try {
            $trackId = resolve($trackIdKey);
        } catch (Exception $e) {
            $trackId = '-';
        }

        return $trackId;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        if (!in_array($name, self::$LOG_LEVELS)) {
            $name = 'debug';
        }

        if (!is_array($arguments)) {
            $arguments = [$arguments];
        }

        // fix the wrong usage of xlog when second parameter is not an array
        if (!isset($arguments[1])) {
            $arguments[1] = [];
        }

        if (!is_array($arguments[1])) {
            $arguments[1] = [$arguments[1]];
        }

        $arguments[1]['sid'] = self::getSessionId();

        $arguments[1]['uip'] = @clientIp();

        // add user id to all logs
        $arguments[1]['uid'] = self::getUserTag(); // user id as a tag

        // get request track ID from service container
        $trackIdKey = self::getTrackIdKey();
        if (!isset($arguments[1][$trackIdKey])) {
            $arguments[1][$trackIdKey] = self::getTrackId($trackIdKey);
        }

        return call_user_func_array(['Illuminate\Support\Facades\Log', $name], $arguments);
    }

    /**
     * @return string
     */
    private static function getSessionId(): string
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return session_id();
        }
        return '';
    }

    /**
     * @return string
     */
    private static function getUserTag(): string
    {
        // add user id to all logs
        if ((bool)env('XLOG_ADD_USERID', true) === false || Auth::guest() === true) {
            return 'user';
        }

        if (self::$firstCall === true) {
            return 'us' . self::$userUserId . 'er';
        }

        self::$firstCall = true;
        self::$userUserId = Auth::user()->id;
        return 'us' . self::$userUserId . 'er';
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (!in_array($name, self::$LOG_LEVELS)) {
            $name = 'debug';
        }

        return self::__callStatic($name, $arguments);
    }
}
