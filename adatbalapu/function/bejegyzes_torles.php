<?php
include __DIR__ . "/database_contr.php";
session_start();

if (!isset($_SESSION["felhasznalo"]) || $_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['bejegyzes_id']) || !isset($_POST['csoport_id'])) {
    echo "Hibás kérés";
    exit;
}

$bejegyzes_id = $_POST['bejegyzes_id'];
$csoport_id = $_POST['csoport_id'];
$felhasznalonev = $_SESSION["felhasznalo"]["felhasználónév"];

$conn = adatb_betoltes();

// Ellenőrizzük, hogy a bejegyzés a felhasználóé-e
$sql = "SELECT FELHASZNALONEV FROM BEJEGYZES WHERE BEJEGYZES_ID = :bejegyzes_id";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":bejegyzes_id", $bejegyzes_id);
if (!oci_execute($stmt)) {
    $e = oci_error($stmt);
    echo "Hiba a lekérdezés során: " . $e['message'];
    exit;
}
oci_fetch($stmt);
$tulajdonos = oci_result($stmt, "FELHASZNALONEV");
oci_free_statement($stmt);

if ($tulajdonos !== $felhasznalonev) {
    echo "Csak a saját bejegyzéseidet törölheted!";
    exit;
}

// Tranzakció kezdete
$success = true;

try {
    // Kép törlése, ha van
    $sql = "DELETE FROM KEPEK WHERE BEJEGYZES_ID = :bejegyzes_id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":bejegyzes_id", $bejegyzes_id);
    if (!oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
        $success = false;
        $e = oci_error($stmt);
        throw new Exception("Kép törlése sikertelen: " . $e['message']);
    }
    oci_free_statement($stmt);

    // Csoport_bejegyzes kapcsolat törlése
    $sql = "DELETE FROM CSOPORT_BEJEGYZES WHERE BEJEGYZES_ID = :bejegyzes_id AND CSOPORT_ID = :csoport_id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":bejegyzes_id", $bejegyzes_id);
    oci_bind_by_name($stmt, ":csoport_id", $csoport_id);
    if (!oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
        $success = false;
        $e = oci_error($stmt);
        throw new Exception("Csoport-bejegyzés kapcsolat törlése sikertelen: " . $e['message']);
    }
    oci_free_statement($stmt);

    // Bejegyzés törlése
    $sql = "DELETE FROM BEJEGYZES WHERE BEJEGYZES_ID = :bejegyzes_id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":bejegyzes_id", $bejegyzes_id);
    if (!oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
        $success = false;
        $e = oci_error($stmt);
        throw new Exception("Bejegyzés törlése sikertelen: " . $e['message']);
    }
    oci_free_statement($stmt);

    if ($success) {
        oci_commit($conn);
        echo "success";
    } else {
        oci_rollback($conn);
        echo "Hiba történt a törlés során";
    }
} catch (Exception $e) {
    oci_rollback($conn);
    echo "Hiba történt a törlés során: " . $e->getMessage();
}

oci_close($conn);
?>