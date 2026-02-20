<?php

use Http\Controllers\UserController;
use Core\Container;

it("can store user", function () {
    $pdo = setupInMemoryDatabase(function (PDO $pdo) {
        $pdo->exec("CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            password TEXT NOT NULL
        )");
    });

    // Set up POST data (used by UserController::store())
    $_POST = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123'
    ];

    // Create controller instance (will use Container to resolve Database)
    $userController = new UserController();

    // Execute the store method
    $userController->store();

    // Verify the user was inserted
    $stmt = $pdo->query("SELECT * FROM users WHERE email = 'test@example.com'");
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    expect($user)->not->toBeNull()
        ->and($user['name'])->toBe('Test User')
        ->and($user['email'])->toBe('test@example.com');
});
