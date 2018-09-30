<?php
ini_set("display_errors", "on");
require_once "../model/message.php";
if (session_status() !== 2) {
    session_start();
    if (!isset($_SESSION['connect'])) {
        header("location: /PHP_my_meetic/view/connectView.php");
        exit;
    }
}
$message = new Message();
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
    <title>Message</title>
</head>
<body class="custom_background_user">
<?php
require_once 'navBar.php';
echo "<input type='hidden' value='" .  $_SESSION['connect'] . "'>";
?>
<div class="container">
<div class="row justify-content-center rounded">
<div class="col-6 custom_light  text-center rounded table-conversation">

    <table class="table-hover align-content-center w-100">
        <tbody class="w-100">
        <?php
        $message->createConversations($_SESSION['connect']);
        ?>
        </tbody>
    </table>
</div>
</div>
    <div class="row justify-content-center rounded d-none" id="messageModal">
        <div class="col-6 custom_light text-center rounded">
            <table id="tableMessage" class="w-100 custom_message table-responsive"></table>
        </div>
    </div>
</div>

<script src="../js/jquery-3.3.1.js"></script>
<script src="../js/my_Jquery_bootstrap.js"></script>
<script src="../js/messageModal.js"></script>
</body>
</html>