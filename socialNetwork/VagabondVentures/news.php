<?php
session_start();
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Actualités</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php include './header.php'; ?>
    <div id="wrapper">

        <main>

            <?php
            // Etape 1: Ouvrir une connexion avec la base de donnée.
            include './config.php';
            include './likesConnection.php';

            // Etape 2: Selectionner les données des tableaux suivants: posts, users, tags et likes 

            $laQuestionEnSql = "
                    SELECT posts.photo_upload, posts.user_id, posts.id, posts.content,
                    posts.created,
                    users.alias as author_name, 
                    count(DISTINCT likes.id) as like_number, posts.user_id, 
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);

            // Vérification
            if (!$lesInformations) {
                echo "<article>";
                echo ("Échec de la requete : " . $mysqli->error);
                echo ("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                exit();
            }

            // NB: à chaque tour du while, la variable post ci dessous reçois les informations du post suivant.
            while ($post = $lesInformations->fetch_assoc()) {
                //echo "<pre>" . print_r($post, 1) . "</pre>";
            ?>
                <article class="gallery">
                    <h3>
                        <time><?php echo $post['created'] ?></time>
                    </h3>
                    <address><a href="wall.php?user_id=<?php echo $post['user_id'] ?>"><?php echo $post['author_name'] ?></a> </address>
                    <p><?php echo $post['content'] ?></p> <br> <br>
                    <div class="divPhoto">
                        <img src="<?php echo $post['photo_upload']; ?>" class="newsPhoto" alt="Portrait de l'utilisatrice" />
                    </div>
                    <footer>
                        <?php include './likes.php'; ?>
                        <a href=""><?php echo $post['taglist'] ?></a>
                    </footer>
                </article>

            <?php
            } ?>
        </main>
    </div>
</body>

</html>