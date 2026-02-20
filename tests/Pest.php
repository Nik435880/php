<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

use Core\Container;
use Core\Database;

if (!function_exists('test_view')) {
    function test_view($view, $data = [])
    {
        return $data;
    }
}

if (!function_exists('test_redirect')) {
    function test_redirect($url, $status = 0)
    {
        return null;
    }
}

/**
 * Initializes a clean in-memory SQLite connection and binds it to the container.
 *
 * @return PDO
 */
function setupInMemoryDatabase(callable $schemaBuilder): PDO
{
    $container = new Container();
    Container::setInstance($container);

    $pdo = new PDO('sqlite::memory:', null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    $schemaBuilder($pdo);

    $container->bind(PDO::class, fn() => $pdo);
    $container->bind(Database::class, fn($container) => new Database($container->make(PDO::class)));

    return $pdo;
}

/**
 * Ensure a usable session with a temporary save path.
 */
function ensureTestSessionStarted(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        $sessionPath = sys_get_temp_dir() . '/test_sessions';
        if (!is_dir($sessionPath)) {
            mkdir($sessionPath, 0777, true);
        }
        ini_set('session.save_path', $sessionPath);
        session_start();
    }
}
