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
    public function get_dreams()
    {
        if (!isset($_COOKIE['user_id'])) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/dashboard");
            exit();
        }
        $result=[];
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(401);
            die();
        }

        $result=$this->dreamRepository->getMyDreams('array');

        http_response_code(200);
        echo json_encode($result);
        die();
    }
    public function isLiked()
    {
        $result = $this->dreamRepository->isLiked( $_POST['dreamId']);
        echo json_encode($result);
        die();
    }
    public function like()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            if (isset($data['dreamId'])) {

                var_dump($data['dreamId']);
                try {
                    $this->dreamRepository->like(intval($data['dreamId']));
                } catch (PDOException $e) {
                    var_dump($e);
                }
            }
            die();
        }
    }
}
