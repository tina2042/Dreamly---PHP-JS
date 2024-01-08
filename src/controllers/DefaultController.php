<?php

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
        if (isset($_COOKIE['user_id'])) {
            // Pobierz identyfikator użytkownika z cookie
            $user_id = $_COOKIE['user_id'];

        } else {

            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/dashboard");
            exit();
        }


        $this->render('main', ["dream"=>$this->dreamRepository->getMyLastDream(), "fdreams"=>$this->dreamRepository->getFriendDreams()]);//po przecinku kolejne zmienne
        
    }
    public function calendar(){
        if (!isset($_COOKIE['user_id'])) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/dashboard");
            exit();
        }

        $this->render('calendar', ["dreams"=>$this->dreamRepository->getMyDreams()]);//po przecinku kolejne zmienne
        
    }
    
}