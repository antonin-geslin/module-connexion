<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
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
    <?php
        session_start();
        if (isset($_SESSION['login'])) {
            $bdd = new PDO('mysql:host=localhost;dbname=moduleconnexion;charset=utf8', 'root', 'root');
            $requete = $bdd->prepare("SELECT * FROM utilisateurs WHERE login = :login");
            $requete->bindParam(':login', $_SESSION['login']);
            $requete->execute();
            $login = '';
            $prenom = '';
            $nom = '';
            $id = '';
            
            while ($row = $requete->fetch()) {
                $id = $row['id'];
                $login_save = $row['login'];
                $login = $row['login'];
                $prenom = $row['prenom'];
                $nom = $row['nom'];
            }
        }
    ?>
    <form class = "mainForm" method="post">
            <label for="login">Login</label>
            <input type="text" name="login" id="login" value="<?php echo $login ?>" onfocus="this.value='';">
            <label for="prenom">First Name</label>
            <input type="text" name="prenom" id="prenom" value="<?php echo $prenom ?>" onfocus="this.value='';">
            <label for="nom">Name</label>
            <input type="text" name="nom" id="nom" value="<?php echo $nom ?>" onfocus="this.value='';">
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
            <label for="password2">Password</label>
            <input type="password" name="password2" id="password2">
        </div>
        <div class="error">
            <p></p>
        </div>
        <button type="submit" name="submit">Modifier profil</button>
    </form>


    <?php
        function checkForm( $login, $password, $password2, $prenom, $nom){
            if (isset($login) && isset($password) && isset($password2) && isset($prenom) && isset($nom)){
                if ($password == $password2){
                    if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+{};:,<.>]).{8,}$/", $password)){
                            return true;
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
        function changeProfile($id,$login, $prenom, $nom, $password) {
            $bdd = new PDO('mysql:host=localhost;dbname=moduleconnexion;charset=utf8', 'root', 'root');
            $requete = $bdd->prepare("UPDATE utilisateurs SET login = :login, prenom = :prenom, nom = :nom, password = :password WHERE id = :id");
            $requete->bindParam(':login', $login);
            $requete->bindParam(':id', $id);
            $requete->bindParam(':prenom', $prenom);
            $requete->bindParam(':nom', $nom);
            $requete->bindParam(':password', $password);
            $requete->execute();
        }

        
        if (isset($_POST['submit'])) {
            if (!$_SESSION['login']) {
                header('location: connexion.php');
            } else {
                $formresult = checkForm($_POST['login'], $_POST['password'], $_POST['password2'], $_POST['prenom'], $_POST['nom']);
                if ($formresult === true) {
                    changeProfile($id, $_POST['login'], $_POST['prenom'], $_POST['nom'], ($_POST['password']));
                    $_SESSION['login'] = $_POST['login'];
                    header('location: index.php');
                } else {
                    echo "<div class='error'><p>" .$formresult. "</p></div>";
                }
            }
            }
    ?>
</body>
</html>