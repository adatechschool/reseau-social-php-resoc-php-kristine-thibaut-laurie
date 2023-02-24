<?php
session_start();
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Mur</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php include './header.php'; ?>
    <div id="wrapper">
        <?php
        $userId = intval($_GET['user_id']);
        ?>

        <?php
        include './config.php';
        ?>

        <aside>
            <?php
            $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();
            //echo "<pre>" . print_r($user, 1) . "</pre>";
            ?>

            <img src="<?php echo $user['photo']; ?>" class="userPhoto" alt="Portrait de l'utilisatrice" />

            <div class="welcomeMessage">
                <h2>Bienvenue <?php echo $user['alias'] ?></h2>
            </div>

            <section>
                <main>
                    <article>
                        <?php
                        $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");

                        // Récupération de la liste des auteurs.
                        $listAuteurs = [];
                        $laQuestionEnSql = "SELECT * FROM users";
                        $lesInformations = $mysqli->query($laQuestionEnSql);
                        $session_actuelle = $_SESSION['connected_id'];
                        while ($user = $lesInformations->fetch_assoc()) {
                            if ($session_actuelle == $user['id']) {
                                $listAuteurs[$user['id']] = $user['alias'];
                            }
                        }

                        // TRAITEMENT DU FORMULAIRE
                        $enCoursDeTraitement = isset($_POST['auteur']);

                        if ($enCoursDeTraitement) {
                            // On récupére ce qu'il y a dans le formulaire.
                            //echo "<pre>" . print_r($_POST, 1) . "</pre>";
                            $authorId = $_POST['auteur'];
                            $postContent = $_POST['message'];
                            $postedPhoto = $_POST['postedPhoto'];

                            $authorId = intval($mysqli->real_escape_string($authorId));
                            $postContent = $mysqli->real_escape_string($postContent);
                            $lInstructionSql = "INSERT INTO posts "
                                . "(id, user_id, content, created, parent_id, photo_upload) "
                                . "VALUES (NULL, "
                                . $authorId . ", "
                                . "'" . $postContent . "', "
                                . "NOW(), "
                                . "NULL,"
                                . "'" . $postedPhoto . "')";
                            echo $lInstructionSql;
                            $ok = $mysqli->query($lInstructionSql);
                            if (!$ok) {
                                echo "Impossible d'ajouter le message: " . $mysqli->error;
                            } else {
                                echo "Message posté en tant que :" . $listAuteurs[$authorId];
                            }
                        }
                        ?>

                        <form action="" method="post" class="messageBox" id="messageBox">
                            <dl>
                                <dt display="hidden"><label for='auteur'>Auteur</label></dt>
                                <dd display="hidden"><select name='auteur'>
                                        <?php
                                        foreach ($listAuteurs as $id => $alias)
                                            echo "<option value='$id'>$alias</option>";
                                        ?>
                                    </select></dd>
                                <dt><label for='message'>Message</label></dt>
                                <dd><textarea name='message'></textarea></dd>
                                <dt><label for='postedPhoto'>photo</label></dt>
                                <dd><textarea name='postedPhoto'></textarea></dd>
                            </dl>
                            <input type='submit' class="btn"><br> <br>
                        </form>

                        <?php
                        // followers 
                        $select_data_followers = "SELECT * FROM followers WHERE followed_user_id = '$userId' AND following_user_id = '$session_actuelle '";
                        $get_data_followers = $mysqli->query($select_data_followers);
                        $fetched_data_followers = $get_data_followers->fetch_assoc();
                        //echo "<pre>" . print_r($fetched_data_followers, 1) . "<pre>";


                        if ($userId == $session_actuelle) {
                        } else if (!$fetched_data_followers) { ?>

                            <form action="" method="post">
                                <input class="btn" name="followers" type='submit' value="S'abonner">
                            </form>

                            <?php

                            $check_follow = isset($_POST["followers"]);
                            if ($check_follow) {
                                $followed_user_id = $userId;
                                //echo "<pre>" . print_r($followed_user_id) . "<pre>";
                                $sql_followers = "INSERT INTO followers "
                                    . "(id, followed_user_id, following_user_id) "
                                    . "VALUES (NULL, "
                                    . $followed_user_id . ", "
                                    . $session_actuelle . "); ";
                                $insert_followers = $mysqli->query($sql_followers);

                                if (!$insert_followers) {
                                    echo "Impossible d'ajouter le follower: " . $mysqli->error;
                                } else {
                                    echo "Vous êtes abonné:";
                                    header('Refresh:0');
                                }
                            }
                        } else if ($fetched_data_followers) { ?>

                            <form action="" method="post">
                                <input class="btn" name="delete" type='submit' value="Se désabonner">
                            </form>

                        <?php }
                        $check_for_delete = isset($_POST["delete"]);
                        if ($check_for_delete) {
                            $followed_user_id = $userId;
                            //echo "<pre>" . print_r($followed_user_id) . "<pre>";
                            $sql_followers_delete = " DELETE FROM followers WHERE followed_user_id = '$followed_user_id' AND following_user_id = '$session_actuelle'";

                            $delete_followers = $mysqli->query($sql_followers_delete);
                            if (!$delete_followers) {
                                echo "Impossible de se désabonner du follower: " . $mysqli->error;
                            } else {
                                echo "Vous êtes désabonné:";
                                header('Refresh:0');
                            }
                        }
                        ?>

                    </article>
            </section>
        </aside>

        <main>
            <?php
            include './likesConnection.php';
            //On récupére tous les messages de l'utilisatrice.

            $laQuestionEnSql = "
            SELECT posts.user_id, posts.content, posts.created, users.alias as author_name, posts.id,                    
            COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist 
            FROM posts
            JOIN users ON  users.id=posts.user_id
            LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
            LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
            LEFT JOIN likes      ON likes.post_id  = posts.id 
            WHERE posts.user_id='$userId' 
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

                        <small>
                            <?php include './likes.php'; ?>
                        </small>
                        <a href="">#<?php echo $post['taglist'] ?></a>
                    </footer>
                </article>
            <?php } ?>
        </main>
    </div>
</body>

</html>