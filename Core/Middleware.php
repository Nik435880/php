<?php

namespace Core;

use Core\Session;

class Middleware
{
    /**
     * Handle a middleware by name.
     *
     * Returns true if the request should continue to the route callback.
     * Returns false if the middleware has already handled the response
     * (e.g. via redirect) and routing should stop.
     */
    public static function handle(?string $name): bool
    {
        if ($name === null) {
            return true;
        }

        switch ($name) {
            case 'auth':
                // Only allow access if a user is in the session
                if (!Session::isset('user')) {
                    \redirect('/');
                    return false;
                }
                return true;

            case 'guest':
                // Only allow guests (no user in the session)
                if (Session::isset('user')) {
                    \redirect('/users');
                    return false;
                }
                return true;

            default:
                // Unknown middleware name, allow by default
                return true;
        }
    }
}
