<?php

namespace Http\Controllers;

use Core\Session;

class PostController extends Controller
{
    public function index()
    {
        $posts = $this->db->query('SELECT * FROM posts')->get();

        return view('posts/index.view', [
            'title' => 'Posts',
            'posts' => $posts
        ]);
    }

    public function create()
    {
        return view('posts/create.view', [
            'title' => 'Create new post'
        ]);
    }

    public function store()
    {
        $this->db->query(
            'INSERT INTO posts(title,content,users_id) VALUES (:title,:content,:users_id)',
            [
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'users_id' => Session::get('user')['id'],
            ]
        );

        return redirect('/posts');
    }
}
