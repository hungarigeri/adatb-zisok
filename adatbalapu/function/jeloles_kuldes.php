<?php
include "database_contr.php";
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['felhasznalo'])) {
    throw new Exception('Bejelentkezés szükséges');
}

if (!isset($_POST['fogado'])) {
    throw new Exception('Érvénytelen kérelem azonosító');
}

$felhasznalo = $_SESSION['felhasznalo']['felhasználónév'];
$fogado = $_POST['fogado'];

$eredmeny = jelolesKuldese($felhasznalo, $fogado);

if ($eredmeny) {
    echo json_encode(['success' => true, 'message' => 'Sikeres jelölés!']);
} else {
    throw new Exception('Sikertelen törlés');
}