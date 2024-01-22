<?php
session_start();
require "conf.php";
global $yhendus;


function isAdmin(){
    return isset($_SESSION['status']) && $_SESSION['status'];}

if (isset($_SESSION["login"]) && isset($_SESSION["!login"]) && isset($_SESSION['status'])) {

    // Display the Registration form only when a user is logged in
    include('registration.php');
}
if(isset($_REQUEST["stop"])){
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE jooksjad SET lopetamisaeg = NOW() WHERE id=?");
    $kask->bind_param("i",$_REQUEST["stop"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
    exit();
}
?>
<script>
    function hideStopLink(linkId) {
        document.getElementById(linkId).style.display = 'none';
    }
</script>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lopp</title>
    <link rel="stylesheet" type="text/css" href="jookja.css">
</head>
<body>
<header>
    <img src="logo.png" id="logo" alt="logo" width="100" height="100">
    <?php
    if(isset($_SESSION['login'])){
        ?>
        <h1 id="loginname"><?="$_SESSION[login]"?></h1>
        <a href="logout.php"   class="logi">Logi välja</a>
        <?php
    } else {
        ?>
        <a id="lingid" href="login.php">Logi sisse</a>

        <?php
    }
    ?>
    <?php
    if(isset($_SESSION['login'])){
        ?>
        <a id="lingid" href="logout.php"></a>
        <?php
    } else {
        ?>
        <a id="lingid" href="registration.php">Registreerimine</a>

        <?php
    }
    ?>
    <?php
    if(isset($_SESSION['login'])){
        ?>
        <?php
    }

    ?>
    <?php
    if (isset($_SESSION["login"]))
    {
    ?>
    <nav id="navmenu">
        <a href="jooksmain.php" id="lingid2">Lisamine</a>
        <a href="start.php" id="lingid2">Start</a>
        <a href="autasustamise.php" id="lingid2">Autasutamise</a>
        <?php if (isAdmin()){?>
            <a href="adminleht.php" id="lingid2">Halduspaneel</a>
        <?php }?>
    </nav>
</header>
<div id="regdiv">
    <h1>Lõpp</h1>
</div>
<table>
    <tr>
        <th>
            Nimi
        </th>
        <th>
            Pereimi
        </th>
        <th>
            Alustamisaeg
        </th>
        <th>
            Lõpetamisaeg
        </th>
        <th>
            Stop
        </th>
    </tr>
    <?php
    global $yhendus;
    $kask=$yhendus->prepare("SELECT id, eesnimi, perenimi,alustamisaeg,lopetamisaeg,result from jooksjad;");
    $kask->bind_result($id,$nimi,$perenimi,$alustamiaeg,$lopitamisaeg,$result);
    $kask->execute();
    while ($kask->fetch()) {
        echo "<tr>";
        $tantsupaar = htmlspecialchars($nimi);
        echo "<td>" . $nimi . "</td>";
        echo "<td>" . $perenimi . "</td>";
        echo "<td>" . $alustamiaeg . "</td>";
        echo "<td>" . $lopitamisaeg . "</td>";
        echo "<td><a href='?stop=$id'>Stop</a></td>";
        echo "</tr>";
    }
    ?>
</table>
<?php
}
?>
</body>
</html>
