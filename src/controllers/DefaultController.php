<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "/app/autoloader.php";

class DefaultController extends AppController {

    
    
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
        $dreams = new DreamRepository;
        $this->render('main', ["dream"=>$dreams->getMyLastDream(), "fdreams"=>$dreams->getFriendDreams()]);//po przecinku kolejne zmienne
        
    }

    public function adding_dream(){
        // Sprawdź, czy użytkownik jest zalogowany
        if (!isset($_SESSION['user_id'])) {
            // Przekieruj na stronę dashboard lub inną
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/dashboard");
            exit();
        }
        $this->render('adding_dream');
    }
    
}