<?php
session_start();
require_once("conf.php");
global $yhendus;
// Check if the login form fields are filled
if (!empty($_POST['login']) && !empty($_POST['pass'])) {
    // Sanitize user input
    $login = htmlspecialchars(trim($_POST['login']));
    $pass = htmlspecialchars(trim($_POST['pass']));

    // Perform additional validation or checks if needed

    // Hash the password for comparison
    $salt = "superpaev";
    $hashedPassword = crypt($pass, $salt);

    // Check if the user exists in the database
    $stmt = $yhendus->prepare("SELECT login, status FROM registration WHERE login=? AND pass=?");
    $stmt->bind_param("ss", $login, $hashedPassword);
    $stmt->bind_result($kasutaja, $onAdmin);
    $stmt->execute();

    // If user is found, create session and redirect
    if ($stmt->fetch()) {
        $_SESSION['tuvastamine'] = 'misiganes';
        $_SESSION['login'] = $login;
        $_SESSION['status'] = $onAdmin;

        $stmt->close();

        if ($onAdmin == 1) {
            header('Location: adminleht.php');
            exit();
        } else {
            header('Location: jooksmain.php');
            exit();
        }
    } else {
        echo "Kasutaja $login või parool on vale";
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Form</title>
    <link rel="stylesheet" href="fromsstyle.css">
</head>
<body>
<div>
    <h1>Logi sisse</h1>

    <form action="" method="post" id="from">
        Logi sisse: <input type="text" name="login"><br>
        Salasõna: <input type="password" name="pass"><br>
        <input type="submit" value="Logi sisse" id="btn">
    </form>
</div>
</body>
</html>
