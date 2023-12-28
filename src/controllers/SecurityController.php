<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "/app/autoloader.php";

class SecurityController extends AppController {

    private $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }
    public function logout(){
        
        session_destroy();

        // Przekieruj na stronę dashboard lub inną
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
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Set session variables
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_email'] = $user->getEmail();

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
        $user = new User($email, $passwordHash, $name, $surname);      
        $this->userRepository->addUser($user);

        return $this->render('login', ['messages' => ['You\'ve been succesfully registrated!']]);
    }

}