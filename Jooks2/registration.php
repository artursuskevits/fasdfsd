<?php
require_once ('conf.php');
global $yhendus;
if (isset($_SESSION['ksautaja']) && isset($_SESSION['ksautajaid']))
    header("Location: ./jooksmain.php");  // redirect the user to the home page
if (isset($_POST['btn'])) {
    $username = $_POST['ksautaja'];
    $passwd = $_POST['salasona'];
    $passwd_again = $_POST['salasona2'];


    // query the database to see if the username is taken
    global $yhendus;
    $kask= $yhendus->prepare("SELECT * FROM registration WHERE login=?");
    $kask->bind_param("s",$username);
    $kask->execute();
    //$query = mysqli_query($yhendus, "SELECT * FROM kasutajad WHERE nimi='$username'");
    if (!$kask->fetch()){

        // create and format some variables for the database
        $id = '';
        $sool='superpaev';
        $krypt=crypt($passwd, $sool);
        $passwd_hashed = $krypt;
        $date_created = time();
        $last_login = 0;
        $status = 1;



        // verify all the required form data was entered
        if ($username != "" && $passwd != "" && $passwd_again != ""){
            // make sure the two passwords match
            if ($passwd === $passwd_again){
                // make sure the password meets the min strength requirements
                if ( strlen($passwd) >= 5 && strpbrk($passwd, "!#$.,:;()")){
                    // insert the user into the database
                    mysqli_query($yhendus, "INSERT INTO registration (login, pass) VALUES ('$username', '$passwd_hashed')");
                    //echo "<script>alert('rrrr')</script>";
// verify the user's account was created
                    $query = mysqli_query($yhendus, "SELECT * FROM registration WHERE login='{$username}'");
                    if (mysqli_num_rows($query) == 1){

                        /* IF WE ARE HERE THEN THE ACCOUNT WAS CREATED! YAY! */
                        /* WE WILL SEND EMAIL ACTIVATION CODE HERE LATER */
//echo "<script>alert('yay')</script>";
                        $success = true;
                    }
                }
                else
                    $error_msg = 'Teie salasõna ei ole piisavalt tugev. Lisa vähem 5 tähte ja 1 erimärk.';
            }
            else
                $error_msg = 'Teie paroolid ei sobinud.';
        }
        else
            $error_msg = 'Palun täitke kõik nõutavad väljad.';
    }
    else
        $error_msg = 'Kasutajanimi <i>'.$username.'</i> on juba hõivatud. Palun kasutage teist.';
}

else
/*$error_msg = 'An error occurred and your account was not created.';*/

?>
<!DOCTYPE html>
<head>


    <meta charset="UTF-8">
    <title>Regestrerimis virm</title>
    <link rel="stylesheet" href="fromsstyle.css">
</head>
<body>
<div>
    <?php

    require_once ('conf.php');
    global $yhendus;
    if (isset($success) && $success){
        echo '<p color="green">Jaa!!! Teie konto on loodud. <a href="./login.php">Klõpsa siia</a> sisselogimiseks!<p>';
    }
    else if (isset($error_msg))
        echo '<p color="red">'.$error_msg.'</p>';

    ?>
</div>
<h1>Registratsion vorm</h1>
<form action="./registration.php" class="form" method="POST" id="from">
    <label for="kasutaja">Kasutaja nimi: </label>
    <input type="text" name="ksautaja" id="kasutaja"> <br>
    <label for="salasona">Salasõna: </label>
    <input type="password" name="salasona" id="salasona"> <br>
    <label for="salasona2">Korda salasõna: </label>
    <input type="password" name="salasona2" id="salasona2"> <br>
    <input type="submit" name="btn" id="btn" value="Loo">
</form>
<p class="center"><br />
    Kas teil on juba konto? <a href="login.php">Logi sisse siin</a>
</p>
</body>
</html>
