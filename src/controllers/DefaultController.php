<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "/app/autoloader.php";

class DefaultController extends AppController {
    private $dreamRepository;

    public function __construct()
    {
        parent::__construct();
        $this->dreamRepository = new DreamRepository;

    }
    public function dashboard()
    {
        $this->render('dashboard');
        
    }

    public function main()
    {   
        // Sprawdź, czy użytkownik jest zalogowany
        if (!isset($_SESSION['user_id'])) {
            // Przekieruj na stronę dashboard lub inną
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/dashboard");
            exit();
        }

        $this->render('main', ["dream"=>$this->dreamRepository->getMyLastDream(), "fdreams"=>$this->dreamRepository->getFriendDreams()]);//po przecinku kolejne zmienne
        
    }
    public function calendar(){
         // Sprawdź, czy użytkownik jest zalogowany
         if (!isset($_SESSION['user_id'])) {
            // Przekieruj na stronę dashboard lub inną
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/dashboard");
            exit();
        }

        $this->render('calendar', ["dreams"=>$this->dreamRepository->getMyDreams()]);//po przecinku kolejne zmienne
        
    }
    
}