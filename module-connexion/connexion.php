<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="style.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
<header>
        <a href="index.php"><h2>Sign-in/Log-in Simulator</h2></a>
        <?php
            session_start();
            if (isset($_SESSION['login'])) {
                $username = $_SESSION['login'];
                echo "<div class='sign_out'><p class='login_check'>Bienvenue, " .strip_tags($username). "!</p><form method='post'><button type='submit' name='signout'>Sign Out</button></form></div>";
                if (isset($_POST['signout'])) {
                    session_destroy();
                    header('location: index.php');
                    exit();
                }
            } else {
                
            }
        ?>
        <div class="nav">
            <a href="connexion.php">connexion</a>
            <a href="inscription.php">inscription</a>
            <a href="profil.php">profil</a>
            <?php
            session_start();
            if ($_SESSION['type'] == 'admin') {
                echo "<a href='admin.php'>admin</a>";
            }
            ?>
        </div>
</header>
    <form class = "mainForm" method="post">
            <label for="login">Login</label>
            <input type="text" name="login" id="login">
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
            <button type="submit" name="submit">Connexion</button>
    </form>
</body>
</html>


<?php 
function checkForm($login, $password){
    $bdd = new PDO('mysql:host=localhost;dbname=moduleconnexion;charset=utf8', 'root', 'root');
    $requete = $bdd->prepare("SELECT * FROM utilisateurs WHERE login = :login AND password = :password");
    $requete->bindParam(':login', $login);
    $requete->bindParam(':password', $password);
    $requete->execute();
    if (isset($login) && isset($password)){
        if ($requete->rowCount() === 1){
            if ($login == 'admin') {
                $_SESSION['type'] = 'admin';
            }
            return true;
        }
        else {
            return ('Login ou mot de passe incorrect');
        }
    }
    else {
        return ('Veuillez remplir tous les champs');
    }
    return false;
}

if (isset($_POST['submit'])) {
    session_start();
    if (!isset($_SESSION['login'])) {
        session_start();
        $formresult = checkForm($_POST['login'], $_POST['password']);
        if ($formresult === true){
            $_SESSION['login'] = $_POST['login'];
            header('Location: index.php');
            exit();
        }
    }
else {
    echo "<p class = 'error_message'>".$formresult."</p>";
}

}

?>