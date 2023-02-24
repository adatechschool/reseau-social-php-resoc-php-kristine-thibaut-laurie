<?php
session_start();
?>
<?php

// TRAITEMENT DU FORMULAIRE.
$enCoursDeTraitement = isset($_POST['email']);

// On recupere les données du formulaire.
if ($enCoursDeTraitement) {
    //echo "<pre>" . print_r($_POST, 1) . "</pre>";
    $emailAVerifier = $_POST['email'];
    $passwdAVerifier = $_POST['motpasse'];

    //On ouvre une connexion avec la base de donnée.
    $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");

    // Pour éviter les injection sql avec les caractères speciaux.
    $emailAVerifier = $mysqli->real_escape_string($emailAVerifier);
    $passwdAVerifier = $mysqli->real_escape_string($passwdAVerifier);

    // On crypte le mot de passe pour éviter d'exposer notre utilisatrice en cas d'intrusion dans nos systèmes.
    $passwdAVerifier = md5($passwdAVerifier);

    // Construction de la requete.
    $lInstructionSql = "SELECT *
                                                FROM users 
                                                WHERE 
                                                email = '" . $emailAVerifier . "'";

    // Vérification de l'utilisateur.
    $res = $mysqli->query($lInstructionSql);

    global $user;
    $user = $res->fetch_assoc();
    if (!$user or $user["password"] != $passwdAVerifier) {
        echo "La connexion a échouée. ";
    } else {
        echo "Votre connexion est un succès : " . $user['alias'] . ".";
        // Se souvenir que l'utilisateur s'est connecté pour la suite.
        $_SESSION['connected_id'] = $user['id'];
        header("Location: wall.php?user_id=" . $_SESSION['connected_id']);
    }
}
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Connexion</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php include './header.php'; ?>
    <div id="wrapper">
        <aside>
            <h2>Présentation</h2>
            <p>Bienvenue sur notre réseau social.</p>
        </aside>
        <main>
            <article>
                <h2>Connexion</h2>
                <form action="" method="post">
                    <input type='hidden' name='???' value='achanger'>
                    <dl>
                        <dt><label for='email'>E-Mail</label></dt>
                        <dd><input type='email' name='email'></dd>
                        <dt><label for='motpasse'>Mot de passe</label></dt>
                        <dd><input type='password' name='motpasse'></dd>
                    </dl>
                    <input type='submit' class="btn">
                </form>
                <p>
                    Pas de compte?
                    <a href='registration.php'>Inscrivez-vous.</a>
                </p>
            </article>
        </main>
    </div>
</body>

</html>