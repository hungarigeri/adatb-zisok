<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Use absolute path for includes
include __DIR__ . "/function/database_contr.php";
session_start();

if (!isset($_SESSION["felhasznalo"])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: csoportok.php");
    exit;
}

$csoport_id = $_GET['id'];
$felhasznalonev = $_SESSION["felhasznalo"]["felhasználónév"];

if (function_exists('sajat_csoport_törlése')) {
    if (sajat_csoport_törlése($csoport_id, $felhasznalonev)) {
        header("Location: csoportok.php?success=1");
    } else {
        header("Location: csoportok.php?error=1");
    }
} else {
    die("Error: sajat_csoport_törlése function not found");
}
exit;
?>