<?php
ini_set("display_errors", "on");

require_once '../model/userModal.php';
require_once '../model/getUser.php';
require_once '../model/searchUser.php';
if (session_status() !== 2) {
    session_start();
    if (!isset($_SESSION['connect'])) {
        header("location: /PHP_my_meetic/view/connectView.php");
        exit;
    }
}
$account = new UserController();
$account->getAllMyInfo($account->id);
$user = new GetUser();
$search = new SearchUser();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/bootstrap-reboot.css">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="../css/custom.css">
    <link rel="stylesheet" href="../css/onglets.css">
    <link rel="stylesheet" href="../css/infobulles.css">
    <title>Compte</title>
</head>
<body class="custom_background_user"><?php
require_once 'navBar.php';
?>

<nav class="navbar navbar-expand-lg custom_light fixed-bar">
<!--    <form method="post" autocomplete="off">-->
        <label> Par Age :
            <?php
            echo $search->createAgeSelect("from", $account->age) . "</label>";
            echo "<label> à ";
            echo $search->createAgeSelect("to", $account->age);
            echo "<input id='sex' type=\"hidden\" value=\"$account->sexSearch\">" .
                 "<input id='sexSearch' type=\"hidden\" value=\"$account->sex\">" .
                 "<input id='city' type='hidden' value='$account->city'>" .
                 "<input id='id' type='hidden' value='$account->id'>";
            ?>
        </label>
        <label> Ville :
            <input type="text" autocomplete="off" id="placeSearch" class="btn btn-light mr-4 ml-1">
        </label>
        <span class="btn btn-outline-primary" data-target="#citySearch2" id="newCity">Ajouter une ville</span>
        <label id="citySearch2"> Ville :
            <input type="text" autocomplete="off" id="placeSearch2" class="btn btn-light mr-4 ml-1">
        </label>
        <label id="citySearch3"> Ville :
            <input type="text" autocomplete="off" id="placeSearch3" class="btn btn-light mr-4 ml-1">
        </label>
        <label>
            <button class="btn btn-outline-success" id="searchButton">Rechercher</button>
        </label>
<!--    </form>-->
</nav>
<br>
<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="card col-4 custom_light rounded d-none" id="slider"
             data-next="#next" data-previous="#previous" data-table="#sliderTable" data-img="#sliderImg">
            <div class="card-header justify-content-center text-center d-flex">
                <button class="d-none btn btn-light align-self-center" id="previous"><i class="fa
                fa-chevron-circle-left"></i></button>
                <img id="sliderImg" class='img-profile h-100 w-100'>
                <button class="d-none btn btn-light align-self-center" id="next"><i class="fa
                fa-chevron-circle-right"></i>
                </button>

            </div>
            <div class="card-body justify-content-center">
                <table id="sliderTable" class="w-100 table-bordered table-hover">
                </table>
            </div>
        </div>
    </div>

</div>
<script src="../js/jquery-3.3.1.js"></script>
<script src="../js/my_Jquery_bootstrap.js"></script>
<script src="../js/baseSearch.js"></script>
</body>
</html>
