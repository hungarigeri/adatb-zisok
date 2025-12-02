<?php
include "database_contr.php";
session_start();

if (!isset($_SESSION["felhasznalo"])) {
    die("Hozzáférés megtagadva!");
}

if (!isset($_POST['uzenet_id'])) {
    die("Hiányzó üzenet azonosító!");
}

$felhasznalonev = $_SESSION["felhasznalo"]["felhasználónév"];
$uzenet_id = $_POST['uzenet_id'];

// Ellenőrizzük, hogy a felhasználó tényleg az üzenet küldője-e
$conn = adatb_betoltes();
$stmt = oci_parse($conn, "SELECT felhasznalonev FROM privat_uzenet WHERE uzenet_id = :uzenet_id");
oci_bind_by_name($stmt, ":uzenet_id", $uzenet_id);
oci_execute($stmt);
oci_fetch($stmt);
$kuldte = oci_result($stmt, "FELHASZNALONEV");
oci_free_statement($stmt);

if ($kuldte != $felhasznalonev) {
    die("Csak a saját üzeneteidet törölheted!");
}

// Üzenet törlése
$stmt = oci_parse($conn, "DELETE FROM privat_uzenet WHERE uzenet_id = :uzenet_id");
oci_bind_by_name($stmt, ":uzenet_id", $uzenet_id);
$siker = oci_execute($stmt);

if ($siker) {
    echo "success";
} else {
    $error = oci_error($stmt);
    die("Törlés sikertelen: " . $error['message']);
}

oci_close($conn);
?>