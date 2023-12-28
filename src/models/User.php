<?php
class User{
    private $user_id;
    private $email;
    private $password;
    private $name;
    private $surname;
    private $photo;

    public function __construct(
        int $user_id,
        string $email,
        string $password,
        string $name,
        string $surname
    ) {
        $this->user_id=$user_id;
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->surname = $surname;
    }
    public function getId(): string 
    {
        return $this->user_id;
    }
    public function getEmail(): string 
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }
    
    public function getPhoto() {
        return $this->photo;
    }

    public function setPhoto($photo) {
         $this->photo=$photo;
    }

}