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

        if( !filter_input( INPUT_POST, 'email', FILTER_VALIDATE_EMAIL ) ){
            http_response_code(403);
            return $this->render('register', ['messages' => ['Email is not correct']]);
        }
        try{
        $user = $this->userRepository->getUser($email);
            if ($user) {
                return $this->render('login', ['messages' => ['User already exists!']]);
            }
        } catch(PDOException $ex){
            return $this->render('register', ['messages' => ['Fill the form']]);
        }

            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $user = new User($email, $passwordHash, $name, $surname);
            $this->userRepository->addUser($user);

            return $this->render('login', ['messages' => ['You\'ve been succesfully registrated!']]);


        
        

    }
    public function delete_user()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(401);
            die();
        }
        if( !filter_input( INPUT_POST, 'email', FILTER_VALIDATE_EMAIL ) ){
            http_response_code(403);
            die();
        }
        $email = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL );

        $this->userRepository->deleteUser($email);

        http_response_code(200);
        die();
    }

}