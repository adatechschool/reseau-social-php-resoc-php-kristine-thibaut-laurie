<!-- iserting like and dislike buttons-->

<?php
    $checkLike = "SELECT * FROM likes WHERE user_id= '" . $session_actuelle . "' AND post_id= '" . $post['id'] . "' ";
    $ok = $mysqli->query($checkLike);
    if ($ok -> num_rows == 0) {?>
        <form action ="" method="post">
        <input name="likes" type='submit' value="♥ <?php echo $post['like_number'] ?>">
        <input name="postId" type='hidden' value=" <?php echo $post['id'] ?>">
        </form> 
        <?php
    } else { ?>
        <form action ="" method="post">
        <input name="dislike" type='submit' value="♥ <?php echo $post['like_number'] ?>">
        <input name="postIdD" type='hidden' value=" <?php echo $post['id'] ?>">
        </form> 
<?php } ?>