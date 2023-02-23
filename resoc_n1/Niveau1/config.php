<?php

//Etape 1: Ouvrir une connexion avec la base de donnée.

$mysqli = new mysqli("localhost", "root", "root", "socialnetwork");

//verification de la connexion 
if ($mysqli->connect_errno) {
    echo ("Échec de la connexion : " . $mysqli->connect_error);
    exit();
}
