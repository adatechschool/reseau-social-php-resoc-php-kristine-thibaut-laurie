<?php
session_start();
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Les message par mot-clé</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
<?php include './header.php'; ?>
    <div id="wrapper">
        <?php
       
        // On récupère le tag_id dans l'URL.
        $tagId = intval($_GET['tag_id']);
        ?>

        <?php
        // On se connecte à la base de données.
        include './config.php';
        ?>

        <aside>
            <?php
            /**
             * Etape 3: récupérer le nom du mot-clé
             */
            $laQuestionEnSql = "SELECT * FROM tags WHERE id= '$tagId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $tag = $lesInformations->fetch_assoc();
            //echo "<pre>" . print_r($tag, 1) . "</pre>";
            ?>

            <section>
                <img src="https://media.giphy.com/media/YkXNjAkG7CfEVx3gcy/giphy.gif" alt="Portrait de l'utilisatrice" id="world"/>
                <h3 id="presentation">Présentation</h3>
                <p id="description">Sur cette page vous trouverez les derniers messages de tous les utilisatrices du site.</p>
            </section>
        </aside>

        <main>
            <?php
            include './likesConnection.php';
            
            // Etape 3: récupérer tous les messages avec un mot clé donné.
            $laQuestionEnSql = "
                    SELECT posts.id, posts.user_id, posts.content,
                    posts.created,
                    users.alias as author_name,  
                    count( DISTINCT likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts_tags as filter 
                    JOIN posts ON posts.id=filter.post_id
                    JOIN users ON users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE filter.tag_id = '$tagId' 
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
                        <?php include './likes.php'?> 
                        <a href="">#<?php echo $post['taglist'] ?></a>
                    </footer>
                </article>
            <?php } ?>
        </main>
    </div>
</body>

</html>