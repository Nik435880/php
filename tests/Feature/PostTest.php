<?php

use Http\Controllers\PostController;
use Core\Container;
use Core\Session;

beforeAll(function () {
    // Map app helpers to test shims
    if (!function_exists('view')) {
        function view($view, $data = [])
        {
            return test_view($view, $data);
        }
    }
    if (!function_exists('redirect')) {
        function redirect($url, $status = 0)
        {
            return test_redirect($url, $status);
        }
    }

    setupInMemoryDatabase(function (PDO $pdo) {
        $pdo->exec("CREATE TABLE posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            content TEXT NOT NULL,
            users_id INTEGER NOT NULL
        )");

        $pdo->exec("INSERT INTO posts (title, content, users_id) VALUES ('Test Post', 'Test Content', 1)");
    });
});

it("can index all posts", function () {


    // Create controller instance (will use Container to resolve Database)
    $postController = new PostController();

    // Execute the index method
    $result = $postController->index();

    // The index method returns view data, check that posts are in the result
    expect($result['posts'])->toHaveCount(1);
});


it("can store post", function () {
    ensureTestSessionStarted();

    // Set up a user in the session (required by PostController::store())
    Session::set('user', ['id' => 1]);

    $_POST = [
        "title" => "title",
        "content" => "content",
    ];
    $postController = new PostController();

    $postController->store();

    // Get PDO from container to query the database
    $pdo = Container::getInstance()->make(PDO::class);
    $stmt = $pdo->query("SELECT * FROM posts WHERE title = 'title'");

    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    expect($post)->not->toBe(null)
        ->and($post["title"])->toBe("title")
        ->and($post["content"])->toBe("content")
        ->and($post["users_id"])->toBe(1);
});
