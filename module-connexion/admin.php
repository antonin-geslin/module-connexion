<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
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
    <div class="tab">
        <table>
            <thead>
            <tr>
                <th>Login</th>
                <th>Prenom</th>
                <th>Nom</th>
                <th>Password</th>
            </tr>
            </thead>
            <tbody>
            <?php 
                $bdd = new PDO('mysql:host=localhost;dbname=moduleconnexion;charset=utf8', 'root', 'root');
                $requete = $bdd->prepare("SELECT * FROM utilisateurs");
                $requete->execute();
                $users = $requete->fetchAll();
                foreach ($users as $user) {
                    echo '<tr>';
                    echo '<td>'.$user['login'].'</td>';
                    echo '<td>'.$user['prenom'].'</td>';
                    echo '<td>'.$user['nom'].'</td>';
                    echo '<td>'.$user['password'].'</td>';
                    echo '</tr>';
                }
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>
