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
    function isAdmin(){
        return isset($_SESSION['status']) && $_SESSION['status'];}


    if (isset($_SESSION["login"]) && isset($_SESSION["!login"]) && isset($_SESSION['status'])) {

        // Display the Registration form only when a user is logged in
        include('registration.php');
    }
    ?>


    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Jooksuvõistlus</title>
        <link rel="stylesheet" type="text/css" href="jookja.css">

    </head>
    <script>
        function sortTable(columnIndex) {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.querySelector("table");
            switching = true;

            while (switching) {
                switching = false;
                rows = table.rows;

                for (i = 1; i < rows.length - 1; i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("td")[columnIndex];
                    y = rows[i + 1].getElementsByTagName("td")[columnIndex];

                    if (isNaN(x.innerHTML)) {
                        shouldSwitch = x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase();
                    } else {
                        shouldSwitch = parseFloat(x.innerHTML) > parseFloat(y.innerHTML);
                    }

                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                        break;
                    }
                }
            }
        }

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
            <a id="lingid4" href="login.php">Logi sisse</a>

            <?php
        }
        ?>
        <?php
        if(isset($_SESSION['login'])){
            ?>
            <a id="lingid2" href="logout.php"></a>
            <?php
        } else {
            ?>
            <a id="lingid3" href="registration.php">Registreerimine</a>

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
        <nav id="navmenu2">
            <?php if (isAdmin()){?>
            <a href="start.php" id="lingid2">Start</a>
            <a href="lopp.php" id="lingid2">Lõpp</a>
            <?php }?>
            <a href="autasustamise.php" id="lingid2">Autasutamise</a>
            <?php if (isAdmin()){?>
                <a href="adminleht.php" id="lingid2">Halduspaneel</a>
            <?php }?>
        </nav>
            <?php
        }
        ?>
    </header>
    <?php if (isset($_SESSION["login"]))
    {
    ?>
    <nav>
    <div id="regdiv">
    <h1 id="tere">Jooksuvõistlus</h1>
    </div>

    <div id="languageModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="Lisa_jooksja">Lisa jooksja</h2>
            <form action="  ?">
                <label for="nimi">Nimi:</label>
                <input type="text" name="nimi" id="nimi">
                <label for="perenimi">Perenimi:</label>
                <input type="text" name="perenimi" id="perenimi">
                <input type="submit" name="jkskls" id="jkskls" value="Lisa jooksja">
            </form>
        </div>
    </div>

    <table>
        <tr>
            <th onclick="sortTable(0)">Nimi</th>
            <th onclick="sortTable(1)">Perenimi</th>
            <th onclick="sortTable(2)">Alustamisaeg</th>
            <th onclick="sortTable(3)">Lõpetamisaeg</th>
            <th onclick="sortTable(4)">Result</th>
        </tr>
    <?php
    global $yhendus;
    $kask=$yhendus->prepare("SELECT id, eesnimi, perenimi,alustamisaeg,lopetamisaeg,result from jooksjad ORDER BY result;");
    $kask->bind_result($id,$nimi,$perenimi,$alustamiaeg,$lopitamisaeg,$result);
    $kask->execute();
    while ($kask->fetch()) {
        echo "<tr>";
        $tantsupaar = htmlspecialchars($nimi);
        echo "<td>" . $nimi . "</td>";
        echo "<td>" . $perenimi . "</td>";
        echo "<td>" . $alustamiaeg . "</td>";
        echo "<td>" . $lopitamisaeg . "</td>";
        echo "<td>" . $result . "</td>";
        echo "</tr>";
    }
    ?>
    </table>
    <br>
    <input type="button" id="lisabtn" name="lisabtn" value="Lisa jooksja" onclick="openModal()">
    <?php
    }
    ?>
    <br>
    <div>
        <?php if (!isset($_SESSION["login"]))
        {
        ?>
                <p id="jookshadtext">Registreerida ja osaleda meie võistluses</p>
    <img id="jooksjad" src="jooksjad.jpg" ALT="ESHKEREEEEEEEE" WIDTH="1000" HEIGHT="600">
            <?php
        }
        ?>
    </div>
    </body>
    </html>