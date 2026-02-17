<?php

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/nav.php';

require_once __DIR__ . '/../partials/banner.php';
?>

<a href="/posts/create" class="text-blue-500 underline">Create new post</a>

<ul>
    <?php foreach ($posts  as $post): ?>
        <li>
            <a href="/posts/<?= $post['id'] ?>">
                <?= htmlspecialchars($post["title"]) ?>
            </a>
        </li>
    <?php endforeach; ?>

</ul>


<?php
require_once __DIR__ . '/../partials/footer.php';
?>