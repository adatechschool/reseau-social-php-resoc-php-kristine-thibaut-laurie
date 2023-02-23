<?php
session_start();
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Administration</title>
    <meta name="author" content="Laurie, Thibaut, Kristine">
    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <?php include './header.php'; ?>
    <?php
    // Etape 1: Ouvrir une connexion avec la base de donnée
    include './config.php';
    ?>

    <div id="wrapper" class='admin'>
        <aside>
            <h2>Mots-clés</h2>
            <?php
            // Etape 2 : trouver tous les mots clés
            $laQuestionEnSql = "SELECT * FROM `tags` LIMIT 50";
            $lesInformations = $mysqli->query($laQuestionEnSql);

            // Vérification
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
                exit();
            }

            // affichage des tags
            while ($tag = $lesInformations->fetch_assoc()) {
                //echo "<pre>" . print_r($tag, 1) . "</pre>";
            ?>
                <article>
                    <h3><?php echo $tag['label'] ?></h3>
                    <p><?php echo $tag['id'] ?></p>
                    <nav>
                        <a href="tags.php?tag_id=<?php echo $tag['id'] ?>">Messages</a>
                    </nav>
                </article>
            <?php } ?>
        </aside>

        <main>
            <h2>Utilisatrices</h2>
            <?php
            // Etape 4 : trouver tous les mots clés
            $laQuestionEnSql = "SELECT * FROM `users` LIMIT 50";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            // Vérification
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
                exit();
            }

            while ($tag = $lesInformations->fetch_assoc()) {
                //echo "<pre>" . print_r($tag, 1) . "</pre>";
            ?>
                <article>
                    <h3><?php echo $tag['alias'] ?></h3>
                    <p><?php echo $tag['id'] ?></p>
                    <nav>
                        <a href="wall.php?user_id=<?php echo $tag['id'] ?>">Mur</a>
                        | <a href="feed.php?user_id=<?php echo $tag['id'] ?>">Flux</a>
                        | <a href="settings.php?user_id=<?php echo $tag['id'] ?>">Paramètres</a>
                        | <a href="followers.php?user_id=<?php echo $tag['id'] ?>">Suiveurs</a>
                        | <a href="subscriptions.php?user_id=<?php echo $tag['id'] ?>">Abonnements</a>
                    </nav>
                </article>
            <?php } ?>
        </main>
    </div>
</body>

</html>