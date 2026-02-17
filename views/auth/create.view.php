<?php


require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/nav.php';
require_once __DIR__ . '/../partials/banner.php';

?>


<main class="flex flex-col m-2 ">

    <form action="/login" method="POST" class="flex flex-col w-full rounded-md gap-2 p-4 md:w-1/2 md:border">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="border rounded-md p-1">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="border rounded-md p-1">
        <button class="bg-blue-500 w-20 h-10 text-white rounded-md">
            Login
        </button>

    </form>

    <?php
    require_once __DIR__ . '/../partials/footer.php';

    ?>
</main>