<?php
$session_actuelle = $_SESSION['connected_id'];
$check_likes = isset($_POST["likes"]);

if ($check_likes) {
    //$post_Id =$post['id'] ;
    //echo "<pre>" . print_r($followed_user_id) . "<pre>";
    $sql_likes = "INSERT INTO likes "
        . "(id, user_id, post_id) "
        . "VALUES (NULL, "
        . $session_actuelle . ", "
        .  $_POST["postId"] . "); ";
    echo $sql_likes;
    $insert_likes = $mysqli->query($sql_likes);

    if (!$insert_likes) {
        echo "Impossible d'ajouter un like: " . $mysqli->error;
    } else {
        echo "Vous avez ajoutez un like";
        header('Refresh:0');
    }
}

$check_dislike = isset($_POST["dislike"]);
if ($check_dislike) {
    $post_Id = $_POST['postIdD'];
    //echo "<pre>" . print_r($followed_user_id) . "<pre>";
    $sql_dislike = "DELETE FROM likes WHERE user_id = $session_actuelle AND post_id = $post_Id";
    echo $sql_dislike;
    $insert_dislike = $mysqli->query($sql_dislike);
    if (!$insert_dislike) {
        echo "Impossible to dislike: " . $mysqli->error;
    } else {
        echo "Vous avez ajoutez un dislike";
        header('Refresh:0');
    }
}

?> 
