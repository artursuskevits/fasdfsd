<?php
session_start();
require "conf.php";
global $yhendus;

if(isset($_REQUEST["nimi"]) && !empty($_REQUEST["nimi"]) && isset($_REQUEST["perenimi"]) && !empty($_REQUEST["perenimi"])){
    global $yhendus;
    $kask=$yhendus->prepare("Insert INTO jooksjad (eesnimi,perenimi) Values(?,?)");
    $kask->bind_param("ss", $_REQUEST["nimi"], $_REQUEST["perenimi"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
    exit();
}
if(isset($_REQUEST["kustuta"])){
    global $yhendus;
    $kask=$yhendus->prepare("DELETE FROM jooksjad  WHERE id=?");
    $kask->bind_param("i",$_REQUEST["kustuta"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
    exit();
}
if(isset($_POST["Stratbuton"])){
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE jooksjad SET alustamisaeg = NOW()");
    $kask->execute();
}
function isAdmin(){
    return isset($_SESSION['status']) && $_SESSION['status'];
}

if (isset($_SESSION["login"]) && isset($_SESSION["!login"]) && isset($_SESSION['status'])) {

    // Display the Registration form only when a user is logged in
    include('registration.php');
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Start</title>
    <link rel="stylesheet" type="text/css" href="jookja.css">
</head>
<script>
    function openModal() {
        document.getElementById('languageModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('languageModal').style.display = 'none';
    }
</script>


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
        <a href="lopp.php" id="lingid2">Lõpp</a>
        <a href="autasustamise.php" id="lingid2">Autasutamise</a>
        <?php if (isAdmin()){?>
            <a href="adminleht.php" id="lingid2">Halduspaneel</a>
        <?php }?>
    </nav>
</header>
<div id="regdiv"> 
    <h1>Start</h1>
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
            Kustuta
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
        echo "<td><a href='?kustuta=$id' id='Link'>Kustuta</a></td>";
        echo "</tr>";
    }
    ?>
</table>
<br>
<p id="tere">Võistluse start: </p>
<form method="post" action="">
<input type="submit" id="Stratbuton" name="Stratbuton" value="Start button">
</form>
<?php
}
?>
</body>
</html>