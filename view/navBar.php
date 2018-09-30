<?php
ini_set("display_errors", "on");
require_once "../model/userModal.php";
$navUser = new UserController();
?>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top navbarScrolled">
    <a href="/PHP_my_meetic/view/searchView.php" class="navbar-brand img-Title"><img alt="Logo Relation Ship"
                src="/PHP_my_meetic/view/images/titleRelationShip.png" class="img-Title"></a>
    <button class="navbar-toggler" type="button" data-target="#navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a href="/PHP_my_meetic/view/searchView.php" class="nav-link">Rechercher</a>
            </li>
            <li class="nav-item">
                <?php
                $anyMessage = $navUser->getNewMessage();
                if ($anyMessage['count'] != false) {
                    echo "<a href=\"/PHP_my_meetic/view/messageView.php\" class=\"nav-link\">Messagerie" .
                         "<i class=\"fa fa-exclamation-circle text-primary\"></i></a>";
                } else {
                    echo "<a href=\"/PHP_my_meetic/view/messageView.php\" class=\"nav-link\">Messagerie</a>";
                }
                ?>
            </li>
            <li class="nav-item">
                <div class="" data-target=".custom-dropdown-content" id="dropdown">
                    <span class="nav-link dropdown-toggle">Mon Compte</span>
                    <div class="custom-dropdown-content d-none">
                        <a href="/PHP_my_meetic/view/UserAccount.php" class="btn btn-secondary">
                            Voir mon Compte</a>
                        <a href="/PHP_my_meetic/view/UserAccount.php?edit=true"
                           class="btn-primary btn">Editer mon Compte</a>
                        <a href="/PHP_my_meetic/view/connectView.php?disconnect=true" class="btn
                        btn-danger">Déconnecter</a>
                    </div>
                </div>
            </li>

        </ul>
    </div>
</nav>
<script src="../js/jquery-3.3.1.js"></script>
<script src="../js/dropdown.js"></script>
