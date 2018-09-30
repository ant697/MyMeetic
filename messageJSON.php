<?php

ini_set("display_errors", 'on');
require_once 'model/my_bdd.php';
require_once 'model/message.php';
$myBdd = new MyBdd();
$bdd = $myBdd->bdd;

function getMessage($idConv)
{
    $message = new Message();
    echo json_encode($message->getMessage($idConv));
}
if (isset($_GET['id'], $_GET['idConv'])) {
    getMessage($_GET['idConv']);
} elseif (isset($_GET['sender'], $_GET['message'], $_GET['idConv'])) {
    addMessage($_GET['message'], $_GET['idConv'], $_GET['sender'], $bdd);
} elseif (isset($_GET['newMessage'], $_GET['idConv'])) {
    $message = new Message();
    echo json_encode($message->getNewMessage($_GET['idConv']));
}

function addMessage($message, $idConversation, $idSender, $bdd)
{
    if ($message !== '') {
        $queryMessage = 'INSERT INTO messages (idConversation, idSender, textContent) ' .
            'VALUES (:idConversation, :idSender, :message)';
        $connexion = $bdd->prepare($queryMessage);
        $connexion->bindParam(":idConversation", $idConversation);
        $connexion->bindParam(":idSender", $idSender);
        $connexion->bindParam(":message", $message);
        $result = $connexion->execute();
        $connexion->closeCursor();
        if ($result) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    } else {
        echo json_encode(false);
    }
}