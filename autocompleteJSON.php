<?php

function autocomplete($bdd)
{
    $search = $_GET['searchautocomplete'] . "%";
    $connexion = $bdd->prepare("SELECT ville_nom FROM villes_france_free WHERE ville_nom LIKE :term LIMIT 10");
    $connexion->bindParam(":term", $search);
    $connexion->execute();
    $result = $connexion->fetchAll(PDO::FETCH_NUM);
    $connexion->closeCursor();
    $list = [];
    for ($i = 0; $i < count($result); $i++) {
        $list[] = "<li class='list-group-item autocomplete-element'>" . $result[$i][0] . "</li>";
    }
    echo json_encode($list);
}

function checkCity($bdd)
{
    $city = $_GET['checkCity'];
    $connexion = $bdd->prepare("SELECT * FROM villes_france_free WHERE ville_nom = :city");
    $connexion->bindParam(":city", $city);
    $connexion->execute();
    $result = $connexion->fetch(PDO::FETCH_ASSOC);
    $connexion->closeCursor();
    if ($result !== false) {
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }
}