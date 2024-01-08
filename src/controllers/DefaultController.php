<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
    $_SESSION['last_activity'] = time();
}

require_once "/app/autoloader.php";

class DefaultController extends AppController {
    private $dreamRepository;

    public function __construct()
    {
        //parent::__construct();
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
        if (isset($_SESSION['last_activity'])) {
            // Limit czasu sesji w sekundach
            $session_lifetime = 60;
        
            // Sprawdź czas ostatniej aktywności
            $inactive_time = time() - $_SESSION['last_activity'];
        
            // Wyloguj użytkownika, jeśli przekroczył limit czasu sesji
            if ($inactive_time > $session_lifetime) {
                session_unset();
                session_destroy();
                $url = "http://$_SERVER[HTTP_HOST]";
                header("Location: {$url}/dashboard");
                exit();
            }
        } else {
            // Jeśli sesja nie istnieje, przekieruj do strony logowania
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/dashboard");
            exit();
        }
        
        // Aktualizacja czasu ostatniej aktywności
        $_SESSION['last_activity'] = time();

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