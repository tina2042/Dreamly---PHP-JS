<?php

class User
{
    private int $user_id;
    private string $email;
    private string $password;
    private string $name;
    private string $surname;
    private string $photo;

    public function __construct(
        string $email,
        string $password,
        string $name,
        string $surname
    )
    {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->surname = $surname;
    }


    public function setId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getId(): int
    {
        return $this->user_id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }


    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function setPhoto($photo): void
    {
        $this->photo = $photo;
    }
}
