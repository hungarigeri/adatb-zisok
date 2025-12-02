<?php
include "database_contr.php";
session_start();
felhasznalo_torles($_SESSION["felhasznalo"]["felhasználónév"], $_SESSION["felhasznalo"]["admin-e"]);
header("Location: logout.php");
?>
