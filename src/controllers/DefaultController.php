<?php
require_once "autoloader.php";

class DefaultController extends AppController
{
    private DreamRepository $dreamRepository;
    private UserRepository $userRepository;
    private CommentRepository $commentRepository;


    public function __construct()
    {

        $this->dreamRepository = new DreamRepository();
        $this->userRepository = new UserRepository();
        $this->commentRepository = new CommentRepository();
    }

    public function dashboard()
    {
        $this->render('dashboard');

    }

    public function main()
    {
        if (isset($_COOKIE['user_id'])) {
            $user_id = $_COOKIE['user_id'];

        } else {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/dashboard");
            exit();
        }


        $this->render('main', ["dream" => $this->dreamRepository->getMyLastDream(),
            "fdreams" => $this->dreamRepository->getFriendDreams(),
            "comments" => $this->commentRepository->getDreamsComments()]);

    }

    public function calendar()
    {
        if (!isset($_COOKIE['user_id'])) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/dashboard");
            exit();
        }

        $this->render('calendar', ["dreams" => $this->dreamRepository->getMyDreams()]);

    }

    public function admin_profile()
    {
        if (!isset($_COOKIE['user_id'])) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/dashboard");
            exit();
        }
        $this->user_profile();
    }

    public function user_profile()
    {
        if (!isset($_COOKIE['user_id'])) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/dashboard");
            exit();
        }
        if (!$this->userRepository->isAdmin($_COOKIE['user_id'])) {
            $this->render('user_profile', ["user" => $this->userRepository->getUser($_COOKIE['user_email']),
                "stats" => $this->userRepository->getUserStats($_COOKIE['user_email'])]);
        } else {
            $this->render('admin_profile', ["user" => $this->userRepository->getUser($_COOKIE['user_email']),
                "stats" => $this->userRepository->getUserStats($_COOKIE['user_email']), "allUsers" => $this->userRepository->getAllUsers()]);
        }
    }

    public function search()
    {
        $result = $this->userRepository->getUsersForSearch($_POST['query']);
        echo json_encode($result);
        die();
    }

    public function add_friend()
    {
        $this->userRepository->addFriend($_POST['user_id']);
        die();
    }
}