<?php

require_once "/app/autoloader.php";

class SecurityController extends AppController {

    public function login()
    {   
        $user = new User('jsnow@pk.edu.pl', 'admin', 'Johnny', 'Snow');
       // $user->setPhoto()
        if (!$this->isPost()) {
            return $this->render('login');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        if ($user->getEmail() !== $email) {
            return $this->render('login', ['messages' => ['User with this email does not exist!']]);
        }

        if ($user->getPassword() !== $password) {
            return $this->render('login', ['messages' => ['Wrong password!']]);
        }

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/main");
    }

    public function register()
    {
        if (!$this->isPost()) {
            return $this->render('register');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];
        $name = $_POST['firstname'];
        $surname = $_POST['surname'];

        //if ($password !== $confirmedPassword) {
          //  return $this->render('register', ['messages' => ['Please provide proper password']]);
        //}

        
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $user = new User($email, $passwordHash, $name, $surname);      

        //$this->userRepository->addUser($user);

        return $this->render('login', ['messages' => ['You\'ve been succesfully registrated!']]);
    }

}