<?php
ini_set("display_errors", "on");
require_once 'model/my_bdd.php';
require_once 'autocompleteJSON.php';
$myBDD = new MyBdd();
$bdd = $myBDD->bdd;
if (isset($_GET['searchautocomplete'])) {
    autocomplete($bdd);
} elseif (isset($_GET['searchUser'])) {
    searchUser($bdd);
} elseif (isset($_GET['checkCity'])) {
    checkCity($bdd);
} elseif (isset($_GET['message'])) {
    sendMessage($bdd);
}

function sendMessage($bdd)
{
    $message = $_GET['message'];
    $idSender = $_GET['sender'];
    $idReceiver = $_GET['receiver'];
    if (($idConversation = checkConversation($bdd, $idSender, $idReceiver)) !== false) {
        addMessage($message, $idConversation, $idSender, $bdd);
    } else {
        if (addConversation($bdd, $idSender, $idReceiver)) {
            $idConversation = checkConversation($bdd, $idSender, $idReceiver);
            addMessage($message, $idConversation, $idSender, $bdd);
        }
    }
}

function checkConversation($bdd, $idSender, $idReceiver)
{
    $queryConversation = "SELECT id FROM conversations WHERE (idSender = :idSender AND idReceiver = :idReceiver) " .
                         "OR (idReceiver = :idSender AND idSender = :idReceiver)";
    $connexion = $bdd->prepare($queryConversation);
    $connexion->bindParam(":idSender", $idSender);
    $connexion->bindParam(":idReceiver", $idReceiver);
    $connexion->execute();
    $idConversation = $connexion->fetch(PDO::FETCH_ASSOC);
    $connexion->closeCursor();
    if ($idConversation !== false) {
        return $idConversation['id'];
    } else {
        return false;
    }
}

function addConversation($bdd, $idSender, $idReceiver)
{
    $connexion = $bdd->prepare('INSERT INTO conversations (idSender, idReceiver) VALUES (:idSender, :idReceiver)');
    $connexion->bindParam(':idSender', $idSender);
    $connexion->bindParam(':idReceiver', $idReceiver);
    $connexion->execute();
    $result = $connexion;
    $connexion->closeCursor();
    return $result;
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

function searchUser($bdd)
{
    $ageFrom = $_GET['from'];
    $sex = $_GET['sex'];
    $sexSearch = $_GET['sexSearch'];
    $ageTo = $_GET['to'];
    $city1 = $_GET['city1'];
    $city2 = $_GET['city2'];
    $city3 = $_GET['city3'];
    $queryMember = "SELECT id, lastName AS 'nom', firstName AS \"prenom\", image, 
                    FLOOR(DATEDIFF(CURDATE(), birthDate) / 365) AS 'age', city AS 'ville' FROM member 
                    WHERE 1 AND (city = :city1 OR city = :city2 OR city = :city3)
                    AND sex = :sex AND sexSearch = :sexSearch AND activ = TRUE
                    AND FLOOR(DATEDIFF(CURDATE(), birthDate) / 365) BETWEEN :from AND :to";
    $connexion = $bdd->prepare($queryMember);
    $connexion->bindParam(":sex", $sex);
    $connexion->bindParam(":sexSearch", $sexSearch);
    $connexion->bindParam(":from", $ageFrom);
    $connexion->bindParam(":to", $ageTo);
    $connexion->bindParam(":city1", $city1);
    $connexion->bindParam(":city2", $city2);
    $connexion->bindParam(":city3", $city3);
    $connexion->execute();
    $result = $connexion->fetchAll(PDO::FETCH_ASSOC);
    $connexion->closeCursor();
    echo json_encode($result);
}

