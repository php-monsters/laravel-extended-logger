<?php

namespace Tartan\Log;

use Illuminate\Support\Facades\Auth;
use Exception;

class XLog
{
    private static $LOG_LEVELS = ['debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency'];

    public function __call($name, $arguments)
    {
        if (!in_array($name, self::$LOG_LEVELS)) {
            $name = 'debug';
        }

        return call_user_func_array(['Illuminate\Support\Facades\Log', $name], $arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        if (session_status() == PHP_SESSION_NONE) {
            $arguments[1]['sid'] = session_id();
        } else {
            $arguments[1]['sid'] = '';
        }

        $arguments[1]['uip'] = @clientIp();

        // add user id to all logs
        if (env('XLOG_ADD_USERID', true)) {
            if (!Auth::guest()) {
                $arguments[1]['uid'] = 'us' . Auth::user()->id . 'er'; // user id as a tag
            }
        }
        $trackIdKey = env('XLOG_TRACK_ID_KEY', 'xTrackId');

        // get request track ID from service container
        if (!isset($arguments[1][$trackIdKey])) {
            $arguments[1][$trackIdKey] = self::getTrackId($trackIdKey);
        }

        return call_user_func_array(['Illuminate\Support\Facades\Log', $name], $arguments);
    }

    /**
     * @param Exception $e
     * @param string $level
     *
     * @return mixed
     */
    public static function exception(Exception $e, $level = 'error')
    {
        $trackIdKey = env('XLOG_TRACK_ID_KEY', 'xTrackId');

        $arguments     = [];
        $arguments [0] = 'exception' . $e->getMessage();
        $arguments [1] = [
            'code' => $e->getCode(),
            'file' => basename($e->getFile()),
            'line' => $e->getLine(),
            $trackIdKey => self::getTrackId($trackIdKey)
        ];

        return call_user_func_array(['XLog', $level], $arguments);
    }

    /**
     * @param $trackIdKey
     *
     * @return string
     */
    protected static function getTrackId($trackIdKey)
    {
        try {
            $trackId = resolve($trackIdKey);
        } catch (Exception $e) {
            $trackId = '-';
        }
        return $trackId;
    }
}
