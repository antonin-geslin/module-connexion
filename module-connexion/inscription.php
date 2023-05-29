<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
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
            <label for="prenom">First Name</label>
            <input type="text" name="prenom" id="prenom">
            <label for="nom">Name</label>
            <input type="text" name="nom" id="nom">
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
            <label for="password2">Password</label>
            <input type="password" name="password2" id="password2">
            <p>Already registred ? Log-in <a class="link" href="connexion.php">here !</a></p>
        <button type="submit" name="submit">Inscription</button>
    </form>
</body>
</html>

<?php


function checkForm($login, $password, $password2, $prenom, $nom){
    $bdd = new PDO('mysql:host=localhost;dbname=moduleconnexion;charset=utf8', 'root', 'root');
    $requete = $bdd->prepare("SELECT * FROM utilisateurs WHERE login = :login");
    $requete->bindParam(':login', $login);
    $requete->execute();
    if (isset($login) && isset($password) && isset($password2) && isset($prenom) && isset($nom)){
        if ($password == $password2){
            if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+{};:,<.>]).{8,}$/", $password)){
                if ($requete->rowCount() === 0){
                    return true;
                }
                else {
                    return ('Ce login est déjà utilisé');
                }
            }
            else {
                return ('Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial');
            }
        } else {
            return ('Les mots de passe ne correspondent pas');
        }
    return false;
}
}


function addToBdd ($login, $password, $prenom, $nom){
    $bdd = new PDO('mysql:host=localhost;dbname=moduleconnexion;charset=utf8', 'root', 'root');
    $requete = $bdd->prepare("INSERT INTO utilisateurs (login, password, prenom, nom) VALUES (:login, :password, :prenom, :nom)");
    $requete->bindParam(':login', $login);
    $requete->bindParam(':password', $password);
    $requete->bindParam(':prenom', $prenom);
    $requete->bindParam(':nom', $nom);
    $requete->execute();
}

if (isset($_POST['submit'])){
    $formresult = checkForm($_POST['login'], $_POST['password'], $_POST['password2'], $_POST['prenom'], $_POST['nom']);
    if ($formresult === true){
        addToBdd($_POST['login'], $_POST['password'], $_POST['prenom'], $_POST['nom']);
        header('Location: connexion.php');
    } else {
        echo "<p class = 'error_message'>".$formresult."</p>";
    }
}
?>