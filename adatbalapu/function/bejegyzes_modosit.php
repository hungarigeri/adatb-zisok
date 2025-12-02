<?php
include  "database_contr.php";
session_start();

if (!isset($_SESSION["felhasznalo"]) || $_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['bejegyzes_id']) || !isset($_POST['uj_szoveg'])) {
    die(json_encode(['error' => 'Hibás kérés']));
}

$bejegyzes_id = $_POST['bejegyzes_id'];
$uj_szoveg = $_POST['uj_szoveg'];
$felhasznalonev = $_SESSION["felhasznalo"]["felhasználónév"];

$conn = adatb_betoltes();

// Ellenőrizzük, hogy a bejegyzés a felhasználóé-e
$sql = "SELECT FELHASZNALONEV FROM BEJEGYZES WHERE BEJEGYZES_ID = :bejegyzes_id";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":bejegyzes_id", $bejegyzes_id);
if (!oci_execute($stmt)) {
    $e = oci_error($stmt);
    die(json_encode(['error' => 'Hiba a lekérdezés során: ' . $e['message']]));
}
oci_fetch($stmt);
$tulajdonos = oci_result($stmt, "FELHASZNALONEV");
oci_free_statement($stmt);

if ($tulajdonos !== $felhasznalonev) {
    die(json_encode(['error' => 'Csak a saját bejegyzéseidet módosíthatod!']));
}

// Bejegyzés frissítése
$sql = "UPDATE BEJEGYZES SET SZOVEG = :uj_szoveg, FELTOLTES_IDEJE = SYSDATE WHERE BEJEGYZES_ID = :bejegyzes_id";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":uj_szoveg", $uj_szoveg);
oci_bind_by_name($stmt, ":bejegyzes_id", $bejegyzes_id);

if (oci_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    $e = oci_error($stmt);
    echo json_encode(['error' => 'Módosítás sikertelen: ' . $e['message']]);
}

oci_close($conn);
?>