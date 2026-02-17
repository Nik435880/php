<?php

namespace Http\Controllers;

use Core\Session;

class AuthController extends Controller
{
    public function create()
    {
        return view("auth/create.view", [
            "title" => "Login",
        ]);
    }

    public function login()
    {
        $user = $this->db->query('SELECT * FROM users WHERE email = :email', [
            'email' => $_POST['email'] ?? '',
        ])->find();

        if ($user && password_verify($_POST['password'] ?? '', $user['password'])) {
            Session::set('user', $user);
            return redirect('/');
        }

        return redirect('/login');
    }

    public function logout()
    {
        Session::unset('user');
        return redirect('/login');
    }
}
