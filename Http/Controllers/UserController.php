<?php

namespace Http\Controllers;


class UserController extends Controller
{

    public function create()
    {
        return view('users/create.view', [
            'title' => 'Register new user'
        ]);
    }

    public function store()
    {

        $this->db->query("INSERT INTO users(name,email,password) VALUES(:name,:email,:password)", [
            "name" => $_POST['name'],
            "email" => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        ]);
    }
}
