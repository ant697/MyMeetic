<?php
ini_set("display_errors", "on");
require_once "my_bdd.php";

class Message extends MyBdd
{
    public function getConversations($id)
    {
        $connexion = $this->bdd->prepare("SELECT id FROM member WHERE activ = FALSE");
        $connexion->execute();
        $idDelete = $connexion->fetchAll(PDO::FETCH_ASSOC);
        $connexion->closeCursor();
        $listId = "(";
        for ($i = 0; $i < count($idDelete); $i++) {
            if ($i < count($idDelete) - 1) {
                $listId .=  $idDelete[$i]['id'] . ", ";
            } else {
                $listId .=  $idDelete[$i]['id'];
            }
        }
        $listId .= ")";
        $queryConv = "SELECT conversations.id AS 'idConv', idSender, idReceiver FROM conversations " .
                     "INNER JOIN member ON member.id = idSender OR member.id = idReceiver WHERE member.id = :id " .
                     "AND idSender NOT IN $listId AND idReceiver NOT IN $listId ORDER BY idConv DESC";
        $connexion = $this->bdd->prepare($queryConv);
        $connexion->bindParam(":id", $id);
        $connexion->execute();
        $result = $connexion->fetchAll(PDO::FETCH_ASSOC);
        $connexion->closeCursor();
        return $result;
    }

    public function getMessage($idConversation)
    {
        $connexion = $this->bdd->prepare("SELECT * FROM messages WHERE idConversation = :idConv ORDER BY sendDate ASC");
        $connexion->bindParam(":idConv", $idConversation);
        $connexion->execute();
        $result = $connexion->fetchAll(PDO::FETCH_ASSOC);
        $connexion->closeCursor();
        $this->readMessage($idConversation);
        return $result;
    }
    public function getNewMessage($idConversation)
    {
        $queryNewMessage = "SELECT * FROM messages WHERE idConversation = :idConv AND isRead = FALSE " .
                           " ORDER BY sendDate ASC";
        $connexion = $this->bdd->prepare($queryNewMessage);
        $connexion->bindParam(":idConv", $idConversation);
        $connexion->execute();
        $result = $connexion->fetchAll(PDO::FETCH_ASSOC);
        $connexion->closeCursor();
        $this->readMessage($idConversation);
        return $result;
    }

    public function readMessage($idConversation)
    {
        $connexion = $this->bdd->prepare("UPDATE messages SET isRead = TRUE WHERE idConversation = :idConv");
        $connexion->bindParam(":idConv", $idConversation);
        $connexion->execute();
        $connexion->closeCursor();
    }


    public function getName($id)
    {
        $connex = $this->bdd->prepare("SELECT CONCAT(firstName, ' ', lastName) AS 'name' FROM member WHERE id = :id");
        $connex->bindParam(":id", $id);
        $connex->execute();
        $result = $connex->fetch(PDO::FETCH_ASSOC);
        $connex->closeCursor();
        if ($result !== false) {
            return $result['name'];
        } else {
            return false;
        }
    }

    public function getImage($id)
    {
        $connex = $this->bdd->prepare("SELECT image FROM member WHERE id = :id");
        $connex->bindParam(":id", $id);
        $connex->execute();
        $result = $connex->fetch(PDO::FETCH_ASSOC);
        $connex->closeCursor();
        if ($result !== false) {
            return $result['image'];
        } else {
            return false;
        }
    }

    public function createConversations($id)
    {
        $conversations = $this->getConversations($id);
        for ($i = 0; $i < count($conversations); $i++) {
            $idConversation = $conversations[$i]['idConv'];
            if (($otherid = $conversations[$i]["idSender"]) !== $id) {
                if (($name = $this->getName($otherid)) !== false) {
                    $image = $this->getImage($otherid);
                    echo "<tr><td data-id='$id' data-conv='$idConversation' data-target='#tableMessage' " .
                         "data-hide='#messageModal' class='btn btn-light w-100 modalConv'>" .
                        "<img class='img-Message' src=\"/PHP_my_meetic/upload/" . $image . "\"" .
                        " alt='Photo de profil'>$name</td></tr>";
                }
            } elseif (($otherid = $conversations[$i]['idReceiver']) !== $id) {
                $image = $this->getImage($otherid);
                if (($name = $this->getName($otherid)) !== false) {
                    echo "<tr><td data-id='$id' data-conv='$idConversation' data-target='#tableMessage' " .
                         "data-hide='#messageModal' class='btn btn-light w-100 modalConv'>" .
                    "<img alt='Photo de profil' class='img-Message' src=\"/PHP_my_meetic/upload/" . $image . "\"" .">" .
                    "$name</td></tr>";
                }
            }
        }
    }

    public function createMessage($id, $otherId, $idConversation)
    {
        $messages = $this->getMessage($idConversation);
        for ($i = 0; $i < count($messages); $i++) {
            $message = $messages[$i]['textContent'];
            if ($messages[$i]['idSender'] == $id) {
                echo "<tr><td class='text-right bg-primary rounded'>$message</td></tr>";
            } else {
                echo "<tr><td class='text-left bg-light rounded'>$message</td></tr>";
            }
        }
    }
}