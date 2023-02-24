<?php
session_start();
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Inscription</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php include './header.php'; ?>

    <div id="wrapper">
        <main>
            <article>
                <h2>Inscription</h2>
                <?php
                //TRAITEMENT DU FORMULAIRE
                $enCoursDeTraitement = isset($_POST['email']);

                if ($enCoursDeTraitement) {
                    //echo "<pre>" . print_r($_POST, 1) . "</pre>";
                    $new_email = $_POST['email'];
                    $new_alias = $_POST['pseudo'];
                    $new_passwd = $_POST['motpasse'];
                    $new_photo = $_POST['photo'];


                    $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");

                    // Petite sécurité.
                    $new_email = $mysqli->real_escape_string($new_email);
                    $new_alias = $mysqli->real_escape_string($new_alias);
                    $new_passwd = $mysqli->real_escape_string($new_passwd);
                    // on crypte le mot de passe pour éviter d'exposer notre utilisatrice en cas d'intrusion dans nos systèmes
                    $new_passwd = md5($new_passwd);

                    // Construction de la requete.
                    $lInstructionSql = "INSERT INTO users (id, email, password, alias, photo) "
                        . "VALUES (NULL, "
                        . "'" . $new_email . "', "
                        . "'" . $new_passwd . "', "
                        . "'" . $new_alias . "', "
                        . "'" . $new_photo . "'"
                        . ");";

                    // Exécution de la requete.
                    $ok = $mysqli->query($lInstructionSql);
                    if (!$ok) {
                        echo "L'inscription a échouée : " . $mysqli->error;
                    } else {
                        echo "Votre inscription est un succès : " . $new_alias;
                        echo " <a href='login.php'>Connectez-vous.</a>";
                    }
                }
                ?>

                <form action="registration.php" method="post">
                    <input type='hidden' name='???' value='achanger'>
                    <label class="selection" for="photo">Choissisez votre photo de profil:</label>
                    <select name="photo" id="photo">
                        <option class="selection" value="./photo/boatSunset.jpg">Boat</option>
                        <option class="selection" value="./photo/mountainClimber.jpg">Mountain Climber</option>
                        <option class="selection" value="./photo/passeport.jpg">Passeport</option>
                    </select>
                    <dl>
                        <dt><label for='pseudo'>Pseudo</label></dt>
                        <dd><input type='text' name='pseudo'></dd>
                        <dt><label for='email'>E-Mail</label></dt>
                        <dd><input type='email' name='email'></dd>
                        <dt><label for='motpasse'>Mot de passe</label></dt>
                        <dd><input type='password' name='motpasse'></dd>
                    </dl>
                    <input type='submit' class="btn">
                </form>
            </article>
        </main>
    </div>
</body>

</html>