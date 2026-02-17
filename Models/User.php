<?php

namespace Models;

class User
{
    public function __construct(
        private string $name,
        private string $email,
        private string $password
    ) {}

    public function getUserName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }
}
