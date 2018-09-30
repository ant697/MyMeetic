<?php
ini_set("display_errors", "on");
require_once "../model/userModal.php";
require_once "../model/userConnexion.php";
if (isset($_GET['disconnect'])) {
    unset($_SESSION['connect']);
}
if (session_status() !== 2) {
    session_start();
}
if (isset($_POST['connect'])) {
//    echo "<script>alert('connect ok')</script>";
    $n = new UserConnexion();
    if (isset($_SESSION['connect']) && $_SESSION['connect'] != null) {
        header("Location: /PHP_my_meetic/view/searchView.php");
        exit;
    }
}
if (isset($_POST['submit'])) {
    $register = new UserController();
    if ($register->checkRegister()) {
        $_SESSION['connect'] = $register->id;
        header("Location: /PHP_my_meetic/view/searchView.php");
        exit;
    }
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
    <link rel="stylesheet" href="../css/infobulles.css">
    <title>Document</title>
</head>
<body class="custom_background_connexion">

<div class="text-center">
    <img src="../view/images/titleRelationShip.png">
</div>

<div class="container">
    <div class="text-center p-2">
        <button class="btn-info rounded" id="btnConnect" data-target="#connectModal" data-dismiss="#registerModal">Se
            connecter</button>
    </div>
    <div class="row justify-content-center" id="registerModal">
        <div class="col-4 rounded custom_light">
            <br>
            <p class="custom_text_color text-center">Embarquez chaque jour pour de nouvelles rencontres</p>
            <hr>
            <form method="post" class="form-group" action="" enctype="multipart/form-data">
                <div id="firstRegister">
                    <div class="form-row row justify-content-center">
                        <br>
                        <label>Je suis :
                            <select class="custom-select rounded btn-light btn" name="sex" id="sexSelect">
                                <option>Sélectionner</option>
                                <option value="M">Un homme</option>
                                <option value="F">Une femme</option>
                            </select>
                        </label>
                        <br>
                    </div>
                    <div class="form-row row justify-content-center">
                        <br>
                        <label>Recherchant :
                            <select class="custom-select rounded btn-light btn" name="sexualSearch" id="sexualSelect">
                                <option>Sélectionner</option>
                                <option value="M">Un homme</option>
                                <option value="F">Une femme</option>
                            </select>
                        </label>
                    </div>
                    <div class="text-center">
                        <div class="form-row rounded justify-content-center tooltip" title="Vous devez être majeur pour
                    vous inscrire">
                            <label>Date de naissance :
                                <br>
                                <input type="date" name="dateBirth" class="rounded btn-light text-center btn
                                inputRegister">
                            </label>
                        </div>
                    </div>
                    <br>
                    <div class="form-row justify-content-center rounded">
                        <span class="btn-success rounded btn-outline-success validRegister btn"
                                data-target="#secondRegister" data-dismiss="#firstRegister">Valider</span>
                    </div>
                </div>
                <div id="secondRegister">
                    <div class="text-center">
                        <div class="form-row rounded justify-content-center tooltip" title="Votre nom de famille"
                             data-placement="right">
                            <label>Nom :
                                <br>
                                <input type="text" class="rounded btn btn-light inputRegister" name="lastName">
                            </label>
                        </div>
                    </div>
                    <br>
                    <div class="text-center">
                        <div class="form-row rounded justify-content-center tooltip" title="Votre prénom"
                             data-placement="right">
                            <label>Prénom :
                                <br>
                                <input type="text" class="rounded btn btn-light inputRegister" name="firstName">
                            </label>
                        </div>
                    </div>
                    <br>
                    <div class="text-center">
                        <div class="form-row rounded justify-content-center tooltip" title="Votre ville"
                             data-placement="right">
                            <label>Ville :
                                <br>
                                <input type="text" autocomplete="nope" class="rounded btn btn-light inputRegister"
                                       id="placeSearch" name="city">
                            </label>
                        </div>
                    </div>
                    <br>
                    <div class="form-row justify-content-center rounded">
                        <span class="btn-success rounded btn-outline-success validRegister btn"
                                data-target="#thirdRegister" data-dismiss="#secondRegister">Valider</span>
                    </div>
                </div>
                <div id="thirdRegister">
                    <div class="text-center">
                        <div class="form-row rounded justify-content-center tooltip"
                             title="Votre photo de profil au format jpg/jpeg/png" data-placement="right">
                            <label>Photo :
                                <br>
                                <input type="file" class="rounded btn btn-light" name="SelectedFile">
                            </label>
                        </div>
                    </div>
                    <br>
                    <div class="form-row justify-content-center rounded">
                        <span class="btn-success rounded btn-outline-success validRegister btn"
                                data-target="#forthRegister" data-dismiss="#thirdRegister">Valider</span>
                    </div>
                </div>
                <div id="forthRegister">
                    <div class="text-center">
                        <div class="form-row rounded justify-content-center tooltip" title="Votre mail au format
                            exemple@exemple.fr" data-placement="right">
                            <label>Adresse e-mail
                                <br>
                                <input type="email" class="rounded btn btn-light inputRegister" name="mail">
                            </label>
                        </div>
                    </div>
                    <br>
                    <div class="text-center">
                        <div class="form-row rounded justify-content-center tooltip" title="8 caractères minimum dont :
                            1 maj, 1 min et 1 chiffre" data-placement="right">
                            <label>Mot de passe
                                <br>
                                <input type="password" class="rounded btn btn-light inputRegister" name="password">
                            </label>
                        </div>
                    </div>
                    <br>
                    <div class="form-row rounded justify-content-center">
                        <label>Confirmez
                            <br>
                            <input type="password" class="rounded btn btn-light" name="confirm">
                        </label>
                    </div>
                    <br>
                    <div class="form-row justify-content-center rounded">
                        <input type="submit" name="submit" class="btn-success rounded btn-outline-success btn">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-center" id="connectModal">
        <div class="col-4 rounded custom_light">
            <br>
            <p class="custom_text_color text-center">Connectez-vous<br>Vous n'êtes pas loin de trouver l'amour</p>
            <hr>
            <br>
            <form method="post" class="form-group" action="" title="../view/userAccount.php">
                    <div class="form-row rounded justify-content-center">
                        <label>Adresse e-mail
                            <input type="email" class="rounded btn-light btn" name="mail">
                        </label>
                    </div>
                    <br>
                    <div class="form-row rounded justify-content-center">
                        <label>Mot de passe
                            <input type="password" name="password" class="rounded btn-light text-center btn">
                        </label>
                    </div>
                    <br>
                    <div class="form-row justify-content-center rounded">
                        <input type="submit" class="btn-success rounded btn-outline-success btn btn-light"
                               name="connect">
                    </div>
                </div>

            </form>
        </div>

    </div><?php




?>
</div>
<script src="../js/jquery-3.3.1.js"></script>
<script src="../js/my_Jquery_bootstrap.js"></script>
<script src="../js/verifyForm.js"></script>
</body>
</html>

