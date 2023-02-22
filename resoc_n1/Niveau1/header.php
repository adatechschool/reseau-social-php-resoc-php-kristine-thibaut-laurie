<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Domine:wght@500&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Frank+Ruhl+Libre:wght@300&display=swap" rel="stylesheet">


        <title>Document</title>
</head>
<body>
    
    <header>
        <img src="photo/vagabondLogo.png" alt="Logo de notre réseau social" class="logo"/>
        <nav id="menu">
            
        <?php if (isset($_SESSION['connected_id'])) { ?>
            <a href="news.php">Actualités</a>
            <a href="wall.php?user_id=<?php echo $_SESSION['connected_id'] ?>" >Mur</a>
            <a href="feed.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Flux</a>
            <a href="tags.php?tag_id=1">Mots-clés</a>
        </nav>
        <nav id="user">
            <a href="#">Profil</a>
            <ul>
                <li><a href="settings.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Paramètres</a></li>
                <li><a href="followers.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Mes suiveurs</a></li>
                <li><a href="subscriptions.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Mes abonnements</a></li>
                <li><a href="registration.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Inscription</a></li>
                <li><a href="login.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Connexion</a></li>
                <li><a href="logout.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Deconnexion</a></li>
                <li><a href="usurpedpost.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Apropriation de posts</a></li>
            </ul>
            <?php } else { ?>
            <a href="login.php">Connexion</a>
        <?php
        } ?>
        </nav>
    
    </header>
</body>
</html>