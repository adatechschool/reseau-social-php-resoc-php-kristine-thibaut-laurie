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
        /**
         * Etape 1: Le mur concerne un utilisateur en particulier
         * La première étape est donc de trouver quel est l'id de l'utilisateur
         * Celui ci est indiqué en parametre GET de la page sous la forme user_id=...
         * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php
         * ... mais en résumé c'est une manière de passer des informations à la page en ajoutant des choses dans l'url
         */
        
        $userId = intval($_GET['user_id']);
        ?>
        <?php
        /**
         * Etape 2: se connecter à la base de donnée
         */
        include './config.php';
        ?>

        <aside>
            <?php
            /**
             * Etape 3: récupérer le nom de l'utilisateur
             */

            $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();

            //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par l'alias et effacer la ligne ci-dessous
            //echo "<pre>" . print_r($user, 1) . "</pre>";
            ?>

            <img src="user.jpg" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez tous les message de l'utilisatrice : <?php echo $user['alias'] ?>

                    (n° <?php echo $userId ?>)
                </p>
                <main>
                    <article>
                        <h2>Poster un message</h2>
                        <?php
                        /**
                         * BD
                         */
                        $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
                        /**
                         * Récupération de la liste des auteurs
                         */
                        $listAuteurs = [];
                        $laQuestionEnSql = "SELECT * FROM users";
                        $lesInformations = $mysqli->query($laQuestionEnSql);
                        $session_actuelle = $_SESSION['connected_id'];
                        while ($user = $lesInformations->fetch_assoc()) {
                            if ($session_actuelle == $user['id']) {
                                $listAuteurs[$user['id']] = $user['alias'];
                            }
                        }


                        /**
                         * TRAITEMENT DU FORMULAIRE
                         */
                        // Etape 1 : vérifier si on est en train d'afficher ou de traiter le formulaire
                        // si on recoit un champs email rempli il y a une chance que ce soit un traitement
                        $enCoursDeTraitement = isset($_POST['auteur']);

                        if ($enCoursDeTraitement) {
                            // on ne fait ce qui suit que si un formulaire a été soumis.
                            // Etape 2: récupérer ce qu'il y a dans le formulaire @todo: c'est là que votre travaille se situe
                            // observez le résultat de cette ligne de débug (vous l'effacerez ensuite)
                            //echo "<pre>" . print_r($_POST, 1) . "</pre>";
                            // et complétez le code ci dessous en remplaçant les ???
                            $authorId = $_POST['auteur'];
                            $postContent = $_POST['message'];


                            //Etape 3 : Petite sécurité
                            // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                            $authorId = intval($mysqli->real_escape_string($authorId));
                            $postContent = $mysqli->real_escape_string($postContent);
                            //Etape 4 : construction de la requete
                            $lInstructionSql = "INSERT INTO posts "
                                . "(id, user_id, content, created, parent_id) "
                                . "VALUES (NULL, "
                                . $authorId . ", "
                                . "'" . $postContent . "', "
                                . "NOW(), "
                                . "NULL);";
                            echo $lInstructionSql;
                            // Etape 5 : execution
                            $ok = $mysqli->query($lInstructionSql);
                            if (!$ok) {
                                echo "Impossible d'ajouter le message: " . $mysqli->error;
                            } else {
                                echo "Message posté en tant que :" . $listAuteurs[$authorId];
                            }
                        }
                        ?>
                        <form action="" method="post">
                            <input type='hidden' name='???' value='achanger'>
                            <dl>
                                <dt><label for='auteur'>Auteur</label></dt>
                                <dd><select name='auteur'>
                                        <?php
                                        foreach ($listAuteurs as $id => $alias)
                                            echo "<option value='$id'>$alias</option>";
                                        ?>
                                    </select></dd>
                                <dt><label for='message'>Message</label></dt>
                                <dd><textarea name='message'></textarea></dd>
                            </dl>
                            <input type='submit'><br> <br>
                            
                        </form>
                    </article>
                    <article>
                        
                            <?php
                                
                                    $select_data_followers = "SELECT * FROM followers WHERE followed_user_id = '$userId' AND following_user_id = '$session_actuelle '";
                                    $get_data_followers = $mysqli->query($select_data_followers);
                                    $fetched_data_followers = $get_data_followers -> fetch_assoc();
                                    //echo "<pre>" . print_r($fetched_data_followers, 1) . "<pre>";


                                    if ($userId == $session_actuelle) {
                                        
                                    } else if (!$fetched_data_followers) { ?>

                                        <form action ="" method="post">
                                        <input name="followers" type='submit' value="S'abonner">
                                        </form> 
                                    
                                    <?php

                                        $check_follow = isset($_POST["followers"]);
                                        if ($check_follow ) {
                                        $followed_user_id = $userId ;
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

                                        <form action ="" method="post">
                                            <input name="delete" type='submit' value="Se désabonner">
                                            </form> 

                                    <?php } 
                                        $check_for_delete = isset($_POST["delete"]);
                                        if ($check_for_delete ) {
                                            $followed_user_id = $userId ;
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
                $check_likes= isset($_POST["likes"]);
                    if ($check_likes ) {
                        //$post_Id =$post['id'] ;
                        //echo "<pre>" . print_r($followed_user_id) . "<pre>";
                        $sql_likes = "INSERT INTO likes "
                    . "(id, user_id, post_id) "
                    . "VALUES (NULL, "
                    . $session_actuelle . ", "
                    .  $_POST["postId"]. "); ";
                    echo $sql_likes;                                    
                        $insert_likes = $mysqli->query($sql_likes);
                
                        if (!$insert_likes) {
                            echo "Impossible d'ajouter un like: " . $mysqli->error;
                        } else {
                            echo "Vous avez ajoutez un like";
                            header('Refresh:0');
                        } 
                    }
            
            ?> 
            <?php
            /**
             * Etape 3: récupérer tous les messages de l'utilisatrice
             */
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

            /**
             * Etape 4: @todo Parcourir les messsages et remplir correctement le HTML avec les bonnes valeurs php
             */
            while ($post = $lesInformations->fetch_assoc()) {

                echo "<pre>" . print_r($post, 1) . "</pre>";
            ?>
                <article>
                    <h3>
                        <time datetime='2020-02-01 11:12:13'><?php echo $post['created'] ?></time>
                    </h3>
                    <address><a href="wall.php?user_id=<?php echo $post['user_id'] ?>"><?php echo $post['author_name'] ?></a></address>
                    <div>
                        <p><?php echo $post['content'] ?></p>
                    </div>
            
                    <footer >         

                        <small>
                            <?php 
                            $post_Id =$post['id'];
                            $checkLike = "SELECT * FROM likes WHERE user_id= '" . $session_actuelle . "' AND post_id= '" . $post['id'] . "' ";
                            $ok = $mysqli->query($checkLike);
                            if ($ok->num_rows == 0) {
                                ?>
                                <form action ="" method="post">
                                    <input name="likes" type='submit' value="♥ <?php echo $post['like_number'] ?>">
                                    <input name="postId" type='hidden' value=" <?php echo $post['id'] ?>">
                                </form> 
                                <?php
                            } else {
                                echo 'liked';
                            } ?>
                        </small>
                        <a href="">#<?php echo $post['taglist'] ?></a>
                    </footer>
                </article>
                <?php } ?> 
           


        </main>
    </div>
</body>

</html>