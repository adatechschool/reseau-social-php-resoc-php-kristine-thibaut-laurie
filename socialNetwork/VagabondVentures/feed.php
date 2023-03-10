<?php
session_start();
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Flux</title>
    <meta name="author" content="">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php include './header.php'; ?>
    <div id="wrapper">
        <?php
        // Etape 1: Le mur concerne un utilisateur en particulier
        $userId = intval($_GET['user_id']);
        ?>

        <?php
        // se connecter à la base de donnée
        include './config.php';
        ?>

        <aside>
            <?php
            // récupérer le nom de l'utilisateur
            $laQuestionEnSql = "SELECT * FROM `users` WHERE id= '$userId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();
            //echo "<pre>" . print_r($user, 1) . "</pre>";
            ?>
            <section>
                <div id="world">
                    <img src="https://media.giphy.com/media/YkXNjAkG7CfEVx3gcy/giphy.gif" alt="Portrait de l'utilisatrice" id="world" />
                </div>
                <section>
                    <h3 id="presentation">Présentation</h3>
                    <p id="description">Sur cette page vous trouverez les derniers messages de
                        tous les utilisatrices du site.</p>
                </section>
            </section>
        </aside>

        <main>
            <?php
            //checker likes et dislikes et inserer dans la base de données
            include './likesConnection.php';
            /**
             * Etape 3: récupérer tous les messages des abonnements
             */
            $laQuestionEnSql = "
                    SELECT posts.id, posts.user_id, posts.content,
                    posts.created,
                    users.alias as author_name,  
                    count(DISTINCT likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM followers 
                    JOIN users ON users.id=followers.followed_user_id
                    JOIN posts ON posts.user_id=users.id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE followers.following_user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }
            while ($post = $lesInformations->fetch_assoc()) {
                //echo "<pre>" . print_r($post, 1) . "</pre>";
            ?>
                <article>
                    <h3>
                        <time datetime='2020-02-01 11:12:13'><?php echo $post['created'] ?></time>
                    </h3>
                    <address><a href="wall.php?user_id=<?php echo $post['user_id'] ?>"><?php echo $post['author_name'] ?></a></address>
                    <div>
                        <p><?php echo $post['content'] ?></p>
                    </div>
                    <footer>
                        <?php include './likes.php' ?>
                        <a href="">#<?php echo $post['taglist'] ?></a>
                    </footer>
                </article>
            <?php
            }
            ?>


        </main>
    </div>
</body>

</html>