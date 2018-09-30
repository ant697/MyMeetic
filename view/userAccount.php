<?php
ini_set("display_errors", "on");

require_once '../model/userModal.php';
require_once '../model/getUser.php';
require_once '../model/updateUser.php';
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
if (isset($_POST['submit'])) {
    $changePass = ($_POST['newPass'] != '') ? true : false;
    $update = new UpdateUser();
    $myPost = ['Email', 'Nom_de_famille', 'Prénom', 'Ville', 'sex', 'sexSearch'];
    if ($update->checkPost($myPost) && $update->update($changePass)) {
        unset($_GET['edit']);
        $account->getAllMyInfo($account->id);
    }
}
if (isset($_POST['delete'])) {
    $user->removeAccount($account->id);
    header("location: /PHP_my_meetic/view/connectView.php?disconnect=true");
    exit;
}
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
<div class="container">
    <div class="row justify-content-center">
        <div class="card col-4 custom_light rounded profil-card">
            <div class="card-header justify-content-center text-center">
                <?php
                $account->createImgProfil();
                ?>
            </div>
            <div class="card-body justify-content-center">
                <?php
                $user->createUserCard($account);
                ?>
            </div>
        </div>
    </div>
</div>
<script src="../js/jquery-3.3.1.js"></script>
<script src="../js/my_Jquery_bootstrap.js"></script>
</body>
</html>
