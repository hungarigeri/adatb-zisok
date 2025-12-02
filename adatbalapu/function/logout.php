<?php
include "database_contr.php";
session_start();
allapotfrissites($_SESSION["felhasznalo"]["felhasználónév"]);
session_unset();
session_destroy();
header("Location: ../index.php");
?>
