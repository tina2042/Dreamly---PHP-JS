<?php

require_once "/app/autoloader.php";

class ErrorController extends AppController {

    
    public function showErrorPage(int $errorCode)
    {
        $errorMessages = [
            404 => 'Page not found',
            500 => 'Internal Server Error',
            403 => 'Forbidden',
            401 => 'Unauthorized', 
            400 => 'Bad Request', 
            503 => 'Service Unavailable', 
            429 => 'Too Many Requests', 
        ];

        $errorMessage = isset($errorMessages[$errorCode]) ? $errorMessages[$errorCode] : 'Unknown Error';

        $this->render('error', ['errorCode' => $errorCode, 'errorMessage' => $errorMessage]);
    }
}

?>
