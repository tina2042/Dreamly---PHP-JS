<?php


require_once "/app/autoloader.php";

class DreamController extends AppController {
    private $dreamRepository;
    private $userRepository;

    public function __construct()
    {
        $this->dreamRepository = new DreamRepository();
        $this->userRepository = new UserRepository();
    }
    public function adding_dream(){

        if (!isset($_COOKIE['user_id'])) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/dashboard");
            exit();
        }

        if (!$this->isPost()) {
            return $this->render('adding_dream');

        }
        $title = $_POST['title'];
        $content = $_POST['content'];
        $date = new DateTime();
        $privacy = $_POST['privacy'];
        if($title == null OR $content == null)
        {
            return $this->render('adding_dream', ['messages'=> ['Title or content can\'t be empty']]);
        }
        $user = $this->userRepository->getUser($_COOKIE['user_email']);
        $dream = new Dream($user, $title, $content, $date,0,0);
        if($privacy!=null)
            $dream->setPrivacy($privacy);
        else{
            $dream->setPrivacy('Public');
        }
        $this->dreamRepository->addDream($dream);
        

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/main?d=1");
        exit();

    }
    public function view_dream(){
        if (!isset($_COOKIE['user_id'])) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/dashboard");
            exit();
        }
        $this->render('view_dream');
    }
}
