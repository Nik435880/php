<?php

use Http\Controllers\AuthController;
use Core\Session;

beforeAll(function () {
    if (!function_exists('redirect')) {
        function redirect($url, $status = 0)
        {
            return test_redirect($url, $status);
        }
    }

    setupInMemoryDatabase(function (PDO $pdo) {
        $pdo->exec("CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            password TEXT NOT NULL
        )");

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute([
            ':name' => 'user',
            ':email' => 'user@gmail.com',
            ':password' => password_hash('password', PASSWORD_DEFAULT),
        ]);
    });
});


it("can login user", function () {
    ensureTestSessionStarted();

    $authController = new AuthController();

    $_POST = [
        "name" => "user",
        "email" => "user@gmail.com",
        // AuthController::login expects the plain password
        "password" => "password",
    ];

    $authController->login();

    expect(Session::get('user'))->not->toBeNull()
        ->and(Session::get('user')['email'])->toBe('user@gmail.com');
});


it("can logout user", function () {
    ensureTestSessionStarted();

    Session::set('user', ['id' => 1, 'email' => 'user@gmail.com']);

    $authController = new AuthController();
    $authController->logout();

    expect(Session::get('user'))->toBeNull();
});
