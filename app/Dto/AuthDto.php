<?php

namespace App\Dto;


final class AuthDto
{
    public string $name;
    public string $email;
    public string $password;


    public function __construct(
        string $name,
        string $email,
        string $password,

    ) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }
}
