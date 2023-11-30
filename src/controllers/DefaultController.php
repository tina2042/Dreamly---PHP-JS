<?php

require_once 'AppController.php';//można zrobic autoloder
require_once __DIR__."/../models/Dream.php";

class DefaultController extends AppController {

    
    
    public function dashboard()
    {
        $this->render('dashboard');
    }

    public function main()
    {
        $dream1 = new Dream(new User('jsnow@pk.edu.pl', 'admin', 'Johnny', 'Snow'), "Flying High", "Soaring through the sky among the clouds.", "2023-11-16", 10, 0);
        $dream2 = new Dream(new User('pmorgan@pk.edu.pl', 'admin', 'Paul', 'Morgan'), "Underwater Adventure", "Exploring a vibrant underwater world.", "2023-11-17", 0, 0);
        $dream3 = new Dream(new User('pp@pk.edu.pl', 'admin', 'Peter', 'Parker'), "Time-Traveling Exploration", "Visiting different historical eras.", "2023-11-18", 0, 0);
        $dream4 = new Dream(new User('ocap@pk.edu.pl', 'admin', 'Oliver', 'Capitan'), "Meeting a Famous Personality", "Encountering a personal hero.", "2023-11-19",10, 30);
        $dream5 = new Dream(new User('gorbus@pk.edu.pl', 'admin', 'George', 'Bush'), "Talking Animal Companion", "Bonding with a wise owl on whimsical adventures.", "2023-11-20", 3,4);
        $dreams = [$dream2, $dream3, $dream4, $dream5];
        $this->render('main', ["dreams"=>$dream1, "fdreams"=>$dreams]);//po przecinku kolejne zmienne
    }

    
}