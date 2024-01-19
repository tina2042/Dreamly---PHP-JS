<?php

require_once "/app/autoloader.php";

class SecurityController extends AppController {

    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }
    public function logout(){
        

        setcookie('user_id', '', time() - 3600, '/');
        setcookie('user_email', '', time()-3600, '/');
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/dashboard");
        exit();
    }
    public function login()
    {   
        
        if (!$this->isPost()) {
            return $this->render('login');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = $this->userRepository->getUser($email);
        
        if (!$user) {
            return $this->render('login', ['messages' => ['User not found!']]);
        }

        if ($user->getEmail() !== $email) {
            return $this->render('login', ['messages' => ['User with this email does not exist!']]);
        }

        if (!password_verify($password, $user->getPassword())) {
            return $this->render('login', ['messages' => ['Wrong password!']]);
        }

        setcookie('user_id', $user->getId(), time() + 3600, '/'); // Ważność 60 min (3600 sekund)
        setcookie('user_email', $user->getEmail(), time()+3600, '/');
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
        
        $user = $this->userRepository->getUser($email);
        
        if ($user) {
            return $this->render('login', ['messages' => ['User already exists!']]);
        }
        
        
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $user = User::getInstance($email, $passwordHash, $name, $surname);
        $this->userRepository->addUser($user);

        return $this->render('login', ['messages' => ['You\'ve been succesfully registrated!']]);
    }

}