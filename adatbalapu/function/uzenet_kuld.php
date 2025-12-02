<?php
include "database_contr.php";
session_start();

if (!isset($_SESSION["felhasznalo"])) {
    die("Hozzáférés megtagadva!");
}

if (!isset($_POST['partner']) || !isset($_POST['uzenet'])) {
    die("Hiányzó adatok!");
}

$felhasznalonev = $_SESSION["felhasznalo"]["felhasználónév"];
$partner = $_POST['partner'];
$uzenet = $_POST['uzenet'];

// Új üzenet azonosító generálása
function ujUzenetId() {
    if (!($conn = adatb_betoltes())) return false;

    $sql = "SELECT NVL(MAX(UZENET_ID), 0) + 1 AS UJ_ID FROM PRIVAT_UZENET";
    $stmt = oci_parse($conn, $sql);
    
    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        die("ID lekérdezés sikertelen: " . $error['message']);
    }
    
    oci_fetch($stmt);
    $uj_id = oci_result($stmt, "UJ_ID");
    oci_free_statement($stmt);
    oci_close($conn);

    return $uj_id;
}

$uzenetId = ujUzenetId();

// Üzenet mentése
if (!($conn = adatb_betoltes())) die("Adatbázis kapcsolódási hiba!");

$sql = "BEGIN UZENET_MENTESE(:id, :szoveg, :cimzett, :felhasznalonev); END;";

$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":id", $uzenetId);
oci_bind_by_name($stmt, ":szoveg", $uzenet);
oci_bind_by_name($stmt, ":cimzett", $partner);
oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);

$siker = oci_execute($stmt);
if (!$siker) {
    $error = oci_error($stmt);
    die("Üzenet mentése sikertelen: " . $error['message']);
}

oci_free_statement($stmt);
oci_close($conn);

echo "Üzenet sikeresen elküldve!";
?>