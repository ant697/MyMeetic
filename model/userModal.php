<?php
ini_set("display_errors", "on");
require_once "my_bdd.php";
require_once "../model/verification.php";
//require_once "../model/newRegistration.php";
class UserController extends MyBdd
{


    private $verification;

    public $id;

    public $registerDate;

    public $currentDate;

    public $image;

    /**
     * Checked City with BDD
     *
     * @var string
     */
    public $city;

    /**
     * Checked Postal Code with BDD
     *
     * @var int
     */
    public $postalCode;

    /**
     * Checked First Name with Regex
     *
     * @var string
     */
    public $firstName;

    /**
     * Checked Last Name with Regex
     *
     * @var string
     */
    public $lastName;

    /**
     * Checked Mail with BDD And Regex
     *
     * @var string
     */
    public $mail;

    /**
     * Checked Password with Regex
     *
     * @var string
     */
    public $password;

    /**
     * Checked Birth Date with Function
     *
     * @var int Date
     */
    public $birthDate;

    /**
     * Checked Age with BDD
     *
     * @var int
     */
    public $age;

    /**
     * Checked Sex with Function
     *
     * @var string(1)
     */
    public $sex;

    /**
     * Checked Sexual Search with Function
     *
     * @var string(1)
     */
    public $sexSearch;


//    public $registration;


    private $passwordHash;

    /**
     * Register constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->verification = new Verification();
        if (isset($_SESSION['connect']) && $_SESSION['connect'] !== null) {
            $this->id = $_SESSION['connect'];
            $this->getAllMyInfo(intval($_SESSION['connect']));
        } else {
            $this->id = false;
        }
    }
 
    public function getNewMessage()
    {
        $query = "SELECT COUNT(*) AS 'count' FROM conversations " .
                 "INNER JOIN messages ON conversations.id = messages.idConversation " .
                 "INNER JOIN member AS m1 ON m1.id = conversations.idSender " .
                 "INNER JOIN member AS m2 ON m2.id = conversations.idReceiver " .
                 "WHERE (conversations.idReceiver = :id OR conversations.idSender = :id) " .
                 "AND m1.activ != FALSE AND m2.activ != FALSE AND messages.isRead = FALSE";
        $connexion = $this->bdd->prepare($query);
        $connexion->bindParam(":id", $this->id);
        $connexion->execute();
        $result = $connexion->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getAllMyInfo($id)
    {
        $connexion = $this->bdd->prepare("SELECT * FROM member WHERE id = $id");
        $connexion->execute();
        $result = $connexion->fetchAll(PDO::FETCH_ASSOC)[0];
        $connexion->closeCursor();
        foreach ($result as $key => $value) {
            $this->$key = $value;
        }
        $this->age = $this->getAge($this->birthDate);
    }

    public function createImgProfil()
    {
        if (isset($_GET['edit'])) {
            echo "<form method='post'>";
            if (isset($_POST['submit'])) {
                // check info
                unset($_GET['edit']);
            }
        }
        if ($this->image !== null) {
            echo "<img src='/PHP_my_meetic/upload/" . $this->image . "' class='img-profile' " .
                "alt='Photo de profil'>";
        } elseif ($this->sex === "M") {
            echo "<img src='/PHP_my_meetic/view/images/avatarHomme.png' class='img-profile' " .
                "alt='Photo de profil'>";
        } elseif ($this->sex === "F") {
            echo "<img src='/PHP_my_meetic/view/images/avatarFemme.jpeg' class='img-profile' " .
                "alt='Photo de profil'>";
        }
    }

    public function checkRegister()
    {
        $myPost = ['city', 'firstName', 'lastName', 'mail', 'password', 'confirm', 'dateBirth', 'sex', 'sexualSearch'];
        foreach ($myPost as $key => $value) {
            if (!isset($_POST[$value])) {
                echo $this->verification->createErrorText("Vous devez remplir tous les champs");
                return false;
            }
        }
        if (!$this->checkPersonalInformation($_POST['firstName'], $_POST['lastName'])
         || !$this->myCheckDate($_POST['dateBirth'])
         || !$this->checkSex($_POST['sex'], $_POST['sexualSearch'])
         || !$this->checkMailPass($_POST['mail'], $_POST['password'], $_POST['confirm'])
         || !$this->checkCity($_POST['city'])
         || !$this->checkImage()) {
            return false;
        } else {
            list($this->city, $this->postalCode) = $this->checkCity($_POST['city']);
            if ($this->insertNewMember()) {
                echo $this->verification->createSuccessText("Inscription effectuée avec succès");
                $this->id = $this->getId($_POST['mail']);
                return true;
            } else {
                $message = "Un problème inconnu est survenu lors de l'inscription Veuillez nous contacter s.v.p";
                echo $this->verification->createErrorText($message);
                return false;
            }
        }
    }

    protected function checkPersonalInformation($firstName, $lastName)
    {
        if (!preg_match($this->verification->nameRegex, $firstName)) {
            echo $this->verification->createErrorText("Prénom Incorrect");
            return false;
        }
        $this->firstName = $firstName;
        if (!preg_match($this->verification->nameRegex, $lastName)) {
            echo $this->verification->createErrorText("Nom Incorrect");
            return false;
        } else {
            $this->lastName = $lastName;
            return true;
        }
    }




    protected function checkMailPass($mail, $password, $confirm)
    {
        if (!preg_match($this->verification->mailRegex, $mail) || $this->checkAlreadyRegister($mail) !==
            false) {
            echo $this->verification->createErrorText("Mail Incorrect");
            return false;
        } else {
            $this->mail = $mail;
        }
        if (!preg_match($this->verification->passRegex, $password) || $password != $confirm) {
            $message = "Le mot de passe doit contenir 8 caractères minimum dont :<br>
                        1 majuscule, 1 minuscule et 2 chiffres";
            echo $this->verification->createErrorText($message);
            return false;
        } else {
            $this->password = $password;
            return true;
        }
    }

    protected function myCheckDate($date)
    {
        $tmp = explode("-", $date);
        if (!checkdate($tmp[1], $tmp[2], $tmp[0]) || !preg_match("/\d{4}-\d{2}-\d{2}/", $date)) {
            echo $this->verification->createErrorText("Date de naissance Incorrecte");
            return false;
        }
        $this->birthDate = $date;
        $age = $this->getAge($this->birthDate);
        if ($age < 18) {
            echo $this->verification->createErrorText("Vous devez avoir 18 ans pour vous connecter");
            return false;
        }
        $this->age = $age;
        $this->currentDate = $this->getCurrentDate();
        return true;
    }

    protected function checkSex($sex, $sexualSearch)
    {
        if (($sex != "M" && $sex !== "F") || ($sexualSearch !== "M" && $sexualSearch !== "F")) {
            echo $this->verification->createErrorText("Vous n'avez pas sélectionné de sexe");
            return false;
        } else {
            $this->sexSearch = $sexualSearch;
            $this->sex = $sex;
            return true;
        }
    }

    protected function getCurrentDate()
    {
        $connexion = $this->bdd->prepare("SELECT CURDATE()");
        $connexion->execute();
        $currentDate = $connexion->fetch(PDO::FETCH_NUM);
        $connexion->closeCursor();
        return $currentDate[0];
    }

    public function insertNewMember()
    {
        $this->passwordHash = $this->hashPassword();
        $queryInsert = "INSERT INTO 
                        member (registerDate, image, firstName, lastName, birthDate, 
                                 sex, sexSearch, city, postalCode, mail, password) 
                        VALUES (:currentDate, :image, :firstName, :lastName, :birthDate, 
                                :sex, :sexualSearch, :city, :postalCode, :mail, :password)";
        $connexion = $this->bdd->prepare($queryInsert);
        $connexion->bindParam(":currentDate", $this->currentDate);
        $connexion->bindParam(":image", $this->image);
        $connexion->bindParam(":firstName", $this->firstName);
        $connexion->bindParam(":lastName", $this->lastName);
        $connexion->bindParam(":birthDate", $this->birthDate);
        $connexion->bindParam(":sex", $this->sex);
        $connexion->bindParam(":sexualSearch", $this->sexSearch);
        $connexion->bindParam(":city", $this->city);
        $connexion->bindParam(":postalCode", $this->postalCode);
        $connexion->bindParam(":mail", $this->mail);
        $connexion->bindParam(":password", $this->passwordHash);
        $success = $connexion->execute();
        $connexion->closeCursor();
        return $success;
    }

    public function hashPassword()
    {
        $password = $this->password;
        $cryptageKey = implode("/", [sha1($this->birthDate), sha1($this->currentDate)]);
        return password_hash(sha1(sha1($password) . sha1($cryptageKey)), PASSWORD_BCRYPT);
    }

    public function checkCity($city)
    {
        $queryCity = "SELECT substr(ville_code_postal, 1, 5) FROM villes_france_free WHERE :city = ville_nom LIMIT 1";
        $connexionCity = $this->bdd->prepare($queryCity);
        $city = preg_replace("[\s]", "-", strtoupper($city));
        $connexionCity->bindParam(":city", $city);
        $connexionCity->execute();
        $postalCode = $connexionCity->fetch(PDO::FETCH_NUM);
        $connexionCity->closeCursor();
        if ($postalCode === false) {
            echo $this->verification->createErrorText("Ville Incorrect");
            return false;
        } else {
            return [$city, $postalCode[0]];
        }
    }

    public function checkAlreadyRegister($mail)
    {
        $connexion = $this->bdd->prepare("SELECT * FROM member WHERE mail = :mail");
        $connexion->bindParam(":mail", $mail);
        $connexion->execute();
        $result = $connexion->fetch();
        $connexion->closeCursor();
        return $result;
    }

    public function getAge($birthDate)
    {
        $connexion = $this->bdd->prepare("SELECT FLOOR(DATEDIFF(CURDATE(), :date) / 365)");
        $connexion->bindParam(":date", $birthDate);
        $connexion->execute();
        $age = $connexion->fetch(PDO::FETCH_NUM);
        $connexion->closeCursor();
        return $age[0];
    }
    public function getCountMember()
    {
        $connexion = $this->bdd->prepare("SELECT COUNT(*) FROM member");
        $connexion->execute();
        $id = $connexion->fetch(PDO::FETCH_NUM)[0];
        $connexion->closeCursor();
        return $id;
    }
    public function checkImage()
    {
        if ($_FILES['SelectedFile']['error'] > 0) {
            echo $this->verification->createErrorText('Il y a eu une erreur durant le téléchargement.');
            return false;
        }
        if (!getimagesize($_FILES['SelectedFile']['tmp_name'])) {
            echo $this->verification->createErrorText('Veuillez choisir une image.');
            return false;
        }
        if ($_FILES['SelectedFile']['type'] != 'image/png' && $_FILES['SelectedFile']['type'] != 'image/jpeg') {
            echo $this->verification->createErrorText('Format non pris en charge.');
            return false;
        }
        if ($_FILES['SelectedFile']['size'] > 500000) {
            echo $this->verification->createErrorText('Fichier trop volumineux.');
            return false;
        }

        if (!$this->addImage(($this->getCountMember() + 1))) {
            return false;
        }
        return true;
    }
    public function addImage($id)
    {
        $ext = "." . strrev(explode(".", strrev($_FILES['SelectedFile']['name']))[0]);
        $name = "-" . $id . "-";
        for ($i = 1; file_exists('../upload/' . $name . $ext); $i++) {
            $name = "-" . $id . "-" . "($i)";
        }
        $this->image = $name . $ext;
        if (!move_uploaded_file($_FILES['SelectedFile']['tmp_name'], '../upload/' . $name . $ext)) {
            echo $this->verification->createErrorText('Il y a eu une erreur durant le téléchargement' .
                ' Veuillez nous contacter S.V.P.');
            return false;
        }
        return true;
    }



    public function getId($mail)
    {
        $connexion = $this->bdd->prepare("SELECT id FROM member WHERE mail = :mail");
        $connexion->bindParam(":mail", $mail);
        $connexion->execute();
        $result = $connexion->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
    }



}

