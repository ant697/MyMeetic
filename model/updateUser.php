<?php
ini_set("display_errors", "on");

require_once "my_bdd.php";
require_once "userModal.php";
class UpdateUser extends UserController
{

    private $verification;

    public $mail;

    public $lastName;

    public $firstName;

    public $city;

    public $postalCode;

    public $sex;

    public $sexSearch;

    /**
     * UpdateUser constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->verification = new Verification();
//        $this->setCity($_POST['Ville']);
    }

    /**
     ** @param mixed $city
     */
    public function setCity($city)
    {
        $connexion = $this->bdd->prepare("SELECT ville_code_postal FROM villes_france_free WHERE ville_nom = :city");
        $connexion->bindParam(":city", $city);
        $connexion->execute();
        $result = $connexion->fetch(PDO::FETCH_NUM);
        if ($result !== false) {
            $this->postalCode = $result[0];
            $this->city = $city;
            return true;
        } else {
            return false;
        }
    }

    public function checkPost($options)
    {
        foreach ($options as $key => $value) {
            if (!isset($_POST[$value])) {
                echo $this->verification->createErrorText("Vous devez remplir tous les champs");
                return false;
            }
        }
        return true;
    }

    private function checkInfo($changePass)
    {

        if (!$this->checkPersonalInformation($_POST['Prénom'], $_POST['Nom_de_famille'])
            || !$this->checkSex($_POST['sex'], $_POST['sexSearch'])
            || !$this->checkCity($_POST['Ville'])
            || !preg_match($this->verification->mailRegex, $_POST['Email'])
            || !$this->checkPassword($_POST['ancienPass'])) {
            return false;
        }
        if ($changePass) {
            if (!preg_match($this->verification->passRegex, $_POST['newPass']) ||
                $_POST['newPass'] !== $_POST['newPassConfirm']) {
                return false;
            } else {
                $this->password = $_POST['newPass'];
                $this->password = $this->hashPassword();
                ($this->password);
            }
        }
        $this->firstName = $_POST['Prénom'];
        $this->lastName = $_POST['Nom_de_famille'];
        $this->sex = $_POST['sex'];
        $this->sexSearch = $_POST['sexSearch'];
        $this->setCity($_POST['Ville']);
        $this->mail = $_POST['Email'];
        return true;
    }

    public function update($changePass)
    {
        if ($this->checkInfo($changePass) && isset($_SESSION['connect'])) {
            $queryUpdate = "UPDATE member SET firstName = :firstName, lastName = :lastName, 
            sex = :sex, sexSearch = :sexSearch, city = :city, postalCode = :postalCode, mail = :mail";
            if ($changePass) {
                $queryUpdate .= ", password = :password";
            }
            $queryUpdate .= " WHERE id = :id";
            $connexion = $this->bdd->prepare($queryUpdate);
            $connexion->bindParam(":firstName", $this->firstName);
            $connexion->bindParam(":lastName", $this->lastName);
            $connexion->bindParam(":sex", $this->sex);
            $connexion->bindParam(":sexSearch", $this->sexSearch);
            $connexion->bindParam(":city", $this->city);
            $connexion->bindParam(":postalCode", $this->postalCode);
            $connexion->bindParam(":mail", $this->mail);
            $connexion->bindParam(":id", $_SESSION['connect']);
            if ($changePass) {
                $connexion->bindParam(":password", $this->password);
            }
            if ($connexion->execute()) {
                echo $this->verification->createSuccessText("Modification effectuée avec succès");
                return true;
            } else {
                echo $this->verification->createErrorText("Il y à eu un problème avec la modification");
                return false;
            }
        } else {
            echo $this->verification->createErrorText("Informations erronées");
        }
    }

    public function checkPassword($password)
    {
        $connexion = $this->bdd->prepare("SELECT password, birthDate, registerDate FROM member WHERE id = :id");
        if (isset($_SESSION['connect'])) {
            $connexion->bindParam(":id", $_SESSION['connect']);
        } else {
            return false;
        }
        $connexion->execute();
        $result = $connexion->fetchAll(PDO::FETCH_ASSOC);
        if ($result !== false) {
            $result = $result[0];
            $hashedPassword = $result['password'];
            $this->birthDate = $result['birthDate'];
            $this->currentDate = $result['registerDate'];
            $cryptageKey = sha1(implode("/", [sha1($result['birthDate']), sha1($result['registerDate'])]));
            if (password_verify(sha1(sha1($password) . $cryptageKey), $hashedPassword)) {
                return true;
            } else {
                echo $this->verification->createErrorText("Mot de passe incorrect");
                return false;
            }
        } else {
            return false;
        }
    }

}