<?php
ini_set("display_errors", "on");
session_start();
require_once "my_bdd.php";
require_once "verification.php";
class UserConnexion extends MyBdd
{

    private $verification;

    /**
     * UserConnexion constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->verification = new Verification();
        if ($this->checkPost()) {
            if ($this->checkPassword($_POST['mail'], $_POST['password'])) {
                $id = $this->getId($_POST['mail']);
                if ($this->checkDisabled($id)) {
                    $_SESSION['connect'] = $id;
                } else {
                    echo $this->verification->createErrorText("Désolé vous avez supprimer votre compte");
                }
            } else {
                if (isset($_SESSION['connect'])) {
                    unset($_SESSION['connect']);
                }
            }
        }
    }

    private function checkDisabled($id)
    {
        $connexion = $this->bdd->prepare("SELECT activ FROM member WHERE id = :id");
        $connexion->bindParam(":id", $id);
        $connexion->execute();
        $result = $connexion->fetch(PDO::FETCH_ASSOC);
        if ($result !== false) {
            return $result['activ'];
        } else {
            return false;
        }
    }

    private function getId($mail)
    {
        $connexion = $this->bdd->prepare("SELECT id FROM member WHERE mail = :mail");
        $connexion->bindParam(":mail", $mail);
        $connexion->execute();
        $result = $connexion->fetch(PDO::FETCH_NUM);
        $connexion->closeCursor();
        if ($result !== false) {
            return $result[0];
        } else {
            return $result;
        }
    }

    private function checkPost()
    {
        $myPost = ['mail', 'password'];
        foreach ($myPost as $value) {
            if (!isset($_POST[$value])) {
                return false;
            }
        }
        return true;
    }

    public function checkPassword($mail, $password)
    {
        if (preg_match($this->verification->mailRegex, $mail)) {
            $hashedPass = $this->getPassword($mail);
            $cryptageKey = $this->getCryptageKey($mail);
            return password_verify(sha1(sha1($password) . sha1($cryptageKey)), $hashedPass);
        } else {
            return false;
        }
    }

    private function getPassword($mail)
    {
        $connexion = $this->bdd->prepare("SELECT password FROM member WHERE mail = :mail");
        $connexion->bindParam(":mail", $mail);
        $connexion->execute();
        $password = $connexion->fetch(PDO::FETCH_NUM);
        $connexion->closeCursor();
        if ($password !== false) {
            return $password[0];
        } else {
            return false;
        }
    }

    private function getCryptageKey($mail)
    {
        $connexion = $this->bdd->prepare("SELECT birthDate, registerDate FROM member WHERE mail = :mail");
        $connexion->bindParam(":mail", $mail);
        $connexion->execute();
        $date = $connexion->fetch(PDO::FETCH_NUM);
        $birthDate = $date[0];
        $registerDate = $date[1];
        return implode("/", [sha1($birthDate), sha1($registerDate)]);
    }

}