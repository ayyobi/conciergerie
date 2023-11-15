<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concierge d'un immeuble - Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body id="signin-bg">
    <?php session_start();

    //Première chose à faire se connecter à la base de données
    define('HOST', 'localhost');
    define('USER', 'root');
    define('PASSWD', '');
    define('DBNAME', 'conciergerie_immeuble');


    try {
        $db = new PDO("mysql:host=". HOST .";dbname=". DBNAME, USER, PASSWD, [
            // Gestion des erreurs PHP/SQL
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
            // Gestion du jeu de caractères
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            // Choix du retours des résultats
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);


        //echo 'Base de données connectée';
    }
    catch (Exception $error) {
        // Attrape une exception
        echo 'Erreur lors de la connexion à la base de données : '. $error->getMessage();
    }


    ?>
<!-- Inscription form -->
    <section class="justify-content-center align-items-center d-flex signin">
        <form method="post" class="signin-form d-flex flex-column justify-content-center">
            <h2 class="text-center sign-title mb-4">Inscription</h2>
            <label for="username" class="sign-label">Nom de utilisateur :</label>
            <input type="text" name="username" value="" required>
            <label for="first-name" class="sign-label mt-3">Prénom :</label>
            <input type="text" name="first-name" value="" required>
            <label for="last-name" class="sign-label mt-3">Nom :</label>
            <input type="text" name="last-name" value="" required>
            <label for="password" class="sign-label mt-3">Mot de passe :</label>
            <input type="password" name="password" value="" required>
            <input type="submit" value="Inscription" class="btn btn-primary mt-4">
            <?php
            // if there is 'username' and 'password' and if they are not empty
    if(isset($_POST['username']) && isset($_POST['password']) && (!empty($_POST['username'])) && (!empty($_POST['password']))){
        // create my variables and secure them
        $username = strip_tags($_POST['username']);
        $password = strip_tags($_POST['password']);
        $password = password_hash($password, PASSWORD_DEFAULT);
        $firstName = strip_tags($_POST['first-name']);
        $lastName = strip_tags($_POST['last-name']);
        // create my request
        $create_user = 'INSERT INTO utilisateurs(name_user, pass_user, firstname_user, lastname_user) VALUES (:username, :password, :firstname, :lastname)';
        $query = $db->prepare($create_user);
        // bind the values
        $query->bindValue(':username', $username, PDO::PARAM_STR);
        $query->bindValue(':password', $password, PDO::PARAM_STR);
        $query->bindValue(':firstname', $firstName, PDO::PARAM_STR);
        $query->bindValue(':lastname', $lastName, PDO::PARAM_STR);
        $query->execute();
        // inform the user his account is created
        echo '<div class="alert alert-success mt-3" role="alert">
        Votre compte a bien été créé, vous pouvez désormais vous connecter.
      </div>';
        header('Refresh:2; url=../index.php');
    }
    ?>
        </form>
    </section>


</body>

</html>