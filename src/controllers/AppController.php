<?php

class AppController {

    //private $request;

    // public function __construct()
    // {
    //     $this->request = $_SERVER['REQUEST_METHOD'];
    // }
    //dodac bez tego i w isGet napisac $_Server['request_method']==='get' i zastosowac singleton, zmienna 
    //server przechpwuje wiele rzeczy tak naprawde
    protected function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    protected function isPost(): bool
    {
        
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function render(string $template = null, array $variables = [])
    {
        $templatePath = 'src/views/'. $template.'.php';
        $output = 'File not found';
                
        if(file_exists($templatePath)){
            extract($variables);
            
            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        } else{
            $errorController = new ErrorController();
            $errorController->showErrorPage(404); 
            return;
        }
        
        print $output;
    }
}