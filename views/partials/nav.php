<?php

use Core\Session;

?>

<nav>
    <ul class="flex flex-row items-center gap-2 h-10">
        <?php if (!Session::isset("user")) : ?>


            <li>
                <a href="/login">Login</a>
            </li>


            <li>
                <a href="/register">Register</a>
            </li>
        <?php endif; ?>


        <?php if (Session::isset("user")) : ?>
            <li>
                <a href="/">Home</a>
            </li>
            <li>
                <a href="/posts">Posts</a>
            </li>
            <li>

                <form action="/logout" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button>
                        Logout
                    </button>
                </form>
            </li>

        <?php endif; ?>

    </ul>
</nav>