<?php
session_start();
require "conf.php";
global $yhendus;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Autasutamise</title>
    <link rel="stylesheet" type="text/css" href="jookja.css">
    <style>
        #top-results {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #top-results h2 {
            color: #ff0066;
            text-align: center;
        }

        .leaderboard {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .leaderboard-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .rank {
            flex: 0 0 30px;
            text-align: center;
            font-size: 1.2em;
        }

        .medal {
            flex: 0 0 30px;
            text-align: center;
            font-size: 1.2em;
        }

        .name {
            flex: 1;
            padding-left: 10px;
        }

        .time {
            flex: 0 0 120px;
            text-align: right;
        }
    </style>

</head>
<body>

<header id="rararar">
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
    if (isset($_SESSION["login"])) {
    ?>
    <nav id="navmenu3">
        <a href="jooksmain.php" id="lingid2">Lisamine</a>
        <?php if (isAdmin()){?>
        <a href="start.php" id="lingid2">Start</a>
        <a href="lopp.php" id="lingid2">Lõpp</a>

            <a href="adminleht.php" id="lingid2">Halduspaneel</a>
        <?php }?>
    </nav>
</header>
<div id="regdiv">
    <h1>Autasutamise</h1>
</div>
<?php } ?>

<?php
function isAdmin(){
    return isset($_SESSION['status']) && $_SESSION['status'];
}

if (isset($_SESSION["login"]) && isset($_SESSION["!login"]) && isset($_SESSION['status'])) {
    // Display the Registration form only when a user is logged in
    include('registration.php');
}

global $yhendus;

// Select all racers and their times
$kask = $yhendus->prepare("SELECT id, eesnimi, perenimi, alustamisaeg, lopetamisaeg FROM jooksjad;");
$kask->bind_result($id, $nimi, $perenimi, $alustamiaeg, $lopitamisaeg);
$kask->execute();

$ResultArray = array();

while ($kask->fetch()) {
    $AlustamiseTime = strtotime($alustamiaeg);
    $LopitamiseTime = strtotime($lopitamisaeg);

    $TimeDifference = $LopitamiseTime - $AlustamiseTime;

    $ResultArray[] = array(
        'id' => $id,
        'nimi' => $nimi,
        'perenimi' => $perenimi,
        'difference' => $TimeDifference,
    );
}

// Sort the array based on the 'difference' key in ascending order
usort($ResultArray, function ($a, $b) {
    return $a['difference'] - $b['difference'];
});

// Update all racers in the database
foreach ($ResultArray as $index => $result) {
    $id = $result['id'];
    $difference = $result['difference'];

    // Update the result in the database
    $update_kask = $yhendus->prepare("UPDATE jooksjad SET result = ? WHERE id = ?");
    $update_kask->bind_param('ii', $difference, $id);
    $update_kask->execute();
}

// Display the top 3 results with ranking
$TOPResults = array_slice($ResultArray, 0, 3);
?>
<div id="top-results">
    <h2>Top 3 Tulemusi</h2>
    <ul class="leaderboard">
        <?php foreach ($TOPResults as $index => $result) : ?>
            <li class="leaderboard-item">
                <span class="rank"><?= $index + 1 ?></span>
                <span class="medal"><?= ($index == 0) ? '🥇' : (($index == 1) ? '🥈' : '🥉') ?></span>
                <span class="name"><?= $result['nimi'] . ' ' . $result['perenimi'] ?></span>
                <span class="time"><?= $result['difference'] ?> seconds</span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
</body>
</html>
