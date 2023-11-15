<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <title>Concierge d'un immeuble  - Connexion</title>
</head>
<body id="login-bg">
    

<?php session_start();

//Première chose à faire se connecter à la base de données

define('HOST', 'localhost');
define('USER', 'root');
define('PASSWD', '');
define('DBNAME', 'conciergerie_immeuble');


try {
	$db = new PDO("mysql:host=". HOST .";dbname=". DBNAME, USER, PASSWD, [
		// errors PHP/SQL
		PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
		// character set
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
		// format of the returned datas
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
	]);

}
catch (Exception $error) {
	// echo the error
	echo 'Erreur lors de la connexion à la base de données : '. $error->getMessage();
}


?>
<!-- CONNEXION FORM -->
<section class ="justify-content-center align-items-center d-flex login">
    <form method="post" class="login-form d-flex flex-column justify-content-center">
        <h2 class="text-center mb-4 login-title">Connexion</h2>
        <label for="username" class="label-login">Nom de utilisateur :</label>
        <input type="text" name="username"  required size="40">
        <label for="password" class="mt-3 label-login">Mot de passe :</label>
        <input type="password" name="password" class="label-login" id="password"  size="40" required>
        <div class="d-flex align-items-center">
            <input type="checkbox" name="display-password" id="checkbox" class="me-2">
            Afficher le mot de passe
        </div>
        <input type="submit" value="Se connecter" class="btn btn-primary mt-4 mb-2">
        <p class="text-center">Pas de compte ? <a href="./signin.php">Créez-le.</a></p>
        <?php 
if(isset($_POST['username']) && isset($_POST['password']) && (!empty($_POST['username'])) && (!empty($_POST['password']))){
    // Here I define my variables and secure them
    $username = strip_tags($_POST['username']);
    $password = strip_tags($_POST['password']);
    // Here I create the query
    $check = 'SELECT * FROM utilisateurs WHERE name_user = :login';
    // I prepare the query
    $query = $db->prepare($check);
    // I bind the login param to my $username input field value
    $query-> bindValue(':login', $username, PDO::PARAM_STR);

    $user= $query->execute();
    // I put the result of the query in the $user variable
    $user = $query->fetch(PDO::FETCH_ASSOC);
    

    // Then I have to check if the username corresponds to what is in my DB
    // So I start by checking if it is different from a username in my DB
    if(!$user){
        echo '<div class="alert alert-danger text-center" role="alert">
        Cet utilisateur n\'existe pas.
                </div>';
        // If it is, do the "else" part and verify the password
    } else {
        if(password_verify($password, $user['pass_user'])){
            $_SESSION['concierge_connected']=true;
            $_SESSION['firstname']= $user['firstname_user'];
            $_SESSION['lastname']= $user['lastname_user'];
            header('location: ../index.php');
        } else {
            echo '<div class="alert alert-danger text-center" role="alert">
            Le mot de passe saisi est invalide.
                </div>';
        }
    }
}
?>
    </form>
</section>



<script src="../js/script.js"></script>

</body>
</html>