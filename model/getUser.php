<?php
ini_set("display_errors", "on");

require_once "my_bdd.php";
class GetUser extends MyBdd
{
    public function createUserCard($account)
    {
        $elementList = ["Email" => 'mail', "Nom de famille" => 'lastName', "Prénom" => 'firstName',
            "Age" => 'age', "Ville" => 'city', "Je suis" => 'sex', "Recherchant" => 'sexSearch'];
        if (isset($_GET['edit'])) {
            echo $this->createCardLine($account, $elementList, true);
        } else {
            echo $this->createCardLine($account, $elementList);
        }
        $this->createEditButton();
    }

    public function createCardLine($account, array $options, $edit = false)
    {
        $str = "<table class='table-hover w-100'><tbody>";
        foreach ($options as $key => $value) {
            switch ($value) {
                case 'city':
                    $info = $this->convertCity($account->$value);
                    $edit = ($edit === false) ? false : "text";
                    break;
                case "sexSearch":
                case 'sex':
                    $info = $this->convertSex($account->$value);
                    $edit = ($edit === false) ? false : "select";
                    break;
                case "mail":
                    $info = $account->$value;
                    $edit = ($edit === false) ? false : "email";
                    break;
                default:
                    $info = $account->$value;
                    $edit = ($edit === false) ? false : "text";
            }
            $str .= $this->checkEdit($edit, $key, $info);
        }
        $str .= $this->addAllForChangePass($edit);
        $str .= "</tbody></table>";
        return $str;
    }

    public function checkEdit($edit, $key, $info)
    {
        $str = "";
        if ($edit !== false && $key != "Age") {
            if ($edit === "select" && $key === "Recherchant") {
                $str .= $this->createSelectHF($key, "sexSearch", $info);
            } elseif ($edit === "select" && $key === "Je suis") {
                $str .= $this->createSelectHF($key, "sex", $info);
            } else {
                $str .= "<tr><td>$key : </td><td class=''><input class='btn btn-light' name='$key' value='$info' 
                        type='$edit'></td></tr>";
            }
        } else {
            $str .= "<tr><td>$key : </td><td class=''>$info</td></tr>";
        }
        return $str;
    }

    public function addAllForChangePass($edit)
    {
        $str = "";
        if ($edit !== false) {
            $str .= "<tr><td>Ancien mot de passe : </td><td><input class='btn btn-light' name='ancienPass' 
                     type='password'></td></tr>";
            $str .= "<tr><td>Nouveau mot de passe : </td><td><input class='btn btn-light' name='newPass' 
                     type='password'></td></tr>";
            $str .= "<tr><td>Confirmez : </td><td><input class='btn btn-light' name='newPassConfirm' 
                     type='password'></td></tr>";
        }
        return $str;
    }

    public function createSelectHF($label, $name, $info)
    {
        $str = "<td><label class='form-inline'>$label :
                            <select class=\"custom-select rounded btn-light btn\" name=\"$name\">
                                <option>Sélectionner</option>";
        if ($info === "un homme") {
            $str .= "<option selected value=\"M\">Un homme</option>";
            $str .= "<option value=\"F\">Une femme</option>";
        } elseif ($info === "une femme") {
            $str .= "<option selected value=\"F\">Une femme</option>";
            $str .= "<option value=\"M\">Un homme</option>";
        }
        $str .= "</select></label></td>";
        return $str;
    }

    public function convertSex($sex)
    {
        if ($sex === "M") {
            return "un homme";
        } elseif ($sex === "F") {
            return "une femme";
        }
    }

    public function convertCity($city)
    {
        $city = strtolower($city);
        return strtoupper($city[0]) . substr($city, 1);
    }

    public function createEditButton()
    {
        if (isset($_GET['edit'])) {
            echo "<br><div class='card-footer text-center'>" .
                "<div title='Cliquez ici pour valider vos informations Personnel' " .
                "data-placement='top' class='tooltip'><button name='submit' class='btn btn-outline-success'>" .
                "Valider</button></div>" .
                "<div title=\"Attention vous n'aurez plus jamais accès a votre compte\" " .
                "data-placement='top' " .
                "class='tooltip'><button class='btn btn-danger' name='delete'>Supprimer</button></div></div></form>";
        } else {
            echo "<br><div class='card-footer text-center'><form method='get'>" .
                "<div title='Cliquez ici pour modifier vos informations Personnel' " .
                "data-placement='top' class='tooltip'><button name='edit' class='btn btn-outline-primary'>" .
                "Editer</button>" . "</div></div></form>";
        }
    }

    public function removeAccount($id)
    {
        $connexion = $this->bdd->prepare("UPDATE member SET activ = FALSE WHERE id = :id");
        $connexion->bindParam(":id", $id);
        $connexion->execute();
        $connexion->closeCursor();
        return $connexion;
    }

}