<?php

namespace Core;

class Session
{
    private static $instance = null;

    private function __construct() {}

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function isset($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function unset($key)
    {
        unset($_SESSION[$key]);
        session_destroy();
    }
}
