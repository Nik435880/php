<?php

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/nav.php';
require_once __DIR__ . '/../partials/banner.php';
?>

<main class="flex flex-col m-2">

    <form action="/posts/create" method="POST" class="flex flex-col w-full rounded-md gap-2 p-4 md:w-1/2 md:border">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" class="border rounded-md p-1" />
        <label for="content">Content</label>
        <textarea name="content" id="content" class="border rounded-md p-1">

    </textarea>

        <button type="submit" class="bg-blue-500 w-20 h-10 text-white rounded-md">
            Submit
        </button>
    </form>

</main>


<?php
require_once __DIR__ . '/../partials/footer.php';
?>