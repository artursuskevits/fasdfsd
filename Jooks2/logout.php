<?php
session_start();
session_destroy();
header("Location: jooksmain.php");
exit();
?>