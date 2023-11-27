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
        $dream1 = new Dream(1, "Flying High", "Soaring through the sky among the clouds.", "2023-11-16");
        $dream2 = new Dream(1, "Underwater Adventure", "Exploring a vibrant underwater world.", "2023-11-17");
        $dream3 = new Dream(1, "Time-Traveling Exploration", "Visiting different historical eras.", "2023-11-18");
        $dream4 = new Dream(1, "Meeting a Famous Personality", "Encountering a personal hero.", "2023-11-19");
        $dream5 = new Dream(1, "Talking Animal Companion", "Bonding with a wise owl on whimsical adventures.", "2023-11-20");
        $dreams = [$dream1, $dream2, $dream3, $dream4, $dream5];
        $this->render('main', ["dreams"=>$dreams]);//po przecinku kolejne zmienne
    }

    
}