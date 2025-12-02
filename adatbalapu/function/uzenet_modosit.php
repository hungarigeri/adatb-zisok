<?php
include "database_contr.php";
session_start();

if (!isset($_SESSION["felhasznalo"])) {
    die(json_encode(['error' => 'Hozzáférés megtagadva!']));
}

if (!isset($_POST['uzenet_id']) || !isset($_POST['uj_szoveg'])) {
    die(json_encode(['error' => 'Hiányzó adatok!']));
}

$felhasznalonev = $_SESSION["felhasznalo"]["felhasználónév"];
$uzenet_id = $_POST['uzenet_id'];
$uj_szoveg = $_POST['uj_szoveg'];

// Ellenőrizzük, hogy a felhasználó tényleg az üzenet küldője-e
$conn = adatb_betoltes();
$stmt = oci_parse($conn, "SELECT felhasznalonev FROM privat_uzenet WHERE uzenet_id = :uzenet_id");
oci_bind_by_name($stmt, ":uzenet_id", $uzenet_id);
oci_execute($stmt);
oci_fetch($stmt);
$kuldte = oci_result($stmt, "FELHASZNALONEV");
oci_free_statement($stmt);

if ($kuldte != $felhasznalonev) {
    die(json_encode(['error' => 'Csak a saját üzeneteidet módosíthatod!']));
}

// Üzenet frissítése
$stmt = oci_parse($conn, "UPDATE privat_uzenet SET szoveg = :uj_szoveg, kuldes_datuma = SYSDATE WHERE uzenet_id = :uzenet_id");
oci_bind_by_name($stmt, ":uj_szoveg", $uj_szoveg);
oci_bind_by_name($stmt, ":uzenet_id", $uzenet_id);
$siker = oci_execute($stmt);

if ($siker) {
    echo json_encode(['success' => true]);
} else {
    $error = oci_error($stmt);
    die(json_encode(['error' => 'Módosítás sikertelen: ' . $error['message']]));
}

oci_close($conn);
?>