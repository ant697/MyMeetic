<?php
session_start();
ini_set("display_errors", "on");
//require_once "model/userModal.php";
class Core
{
    private $user;
    public function __construct()
    {
//        $this->user = new UserController();
    }
    public function callPage()
    {

        if (!isset($_SESSION['connect'])) {
            // require_once "../view/connectView.php";
//            $this->user->showConnect();
            $this->showConnect();
        } else {
            $this->showSearch();
        }
    }

    private function showConnect()
    {
        header("location: /PHP_my_meetic/view/connectView.php");
    }

    private function showSearch()
    {
        header("location: /PHP_my_meetic/view/searchView.php");
     }
}
$redirection = new Core();
$redirection->callPage();
//class ConnectController
//{
//    public function checkConnection()
//    {
//    }
//}
//$index = new ConnectController();
//$index->checkConnection();
