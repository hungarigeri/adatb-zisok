<?php
include "database_contr.php";
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["felhasznalo"])) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['csoport_id'])) {
    header("Location: ../csoportok.php");
    exit;
}

$csoport_id = $_POST['csoport_id'];
$felhasznalonev = $_SESSION["felhasznalo"]["felhasználónév"];
$szoveg = $_POST['szoveg'];

// Ellenőrzés, hogy a felhasználó tagja-e a csoportnak
$conn = adatb_betoltes();
$check_sql = "SELECT COUNT(*) AS DB FROM CSOPORT_TAGJA 
              WHERE CSOPORT_ID = :csoport_id AND FELHASZNALONEV = :felhasznalonev";
$check_stmt = oci_parse($conn, $check_sql);
oci_bind_by_name($check_stmt, ":csoport_id", $csoport_id);
oci_bind_by_name($check_stmt, ":felhasznalonev", $felhasznalonev);

if (!oci_execute($check_stmt)) {
    $error = oci_error($check_stmt);
    error_log("Tagjogosultság ellenőrzése sikertelen: " . $error['message']);
    header("Location: ../csoport_reszletek.php?id=" . $csoport_id . "&error=1");
    exit;
}

oci_fetch($check_stmt);
$is_member = (oci_result($check_stmt, "DB") > 0);
oci_free_statement($check_stmt);

if (!$is_member) {
    header("Location: ../csoportok.php?error=not_member");
    exit;
}

// Tranzakció indítása
oci_execute(oci_parse($conn, "BEGIN"));

try {
    // Bejegyzés létrehozása
    $insert_sql = "INSERT INTO BEJEGYZES (BEJEGYZES_ID, SZOVEG, FELTOLTES_IDEJE, FELHASZNALONEV) 
                   VALUES (bejegyzes_id_seq.NEXTVAL, :szoveg, SYSDATE, :felhasznalonev) 
                   RETURNING BEJEGYZES_ID INTO :bejegyzes_id";
    $insert_stmt = oci_parse($conn, $insert_sql);
    $bejegyzes_id = null;
    
    oci_bind_by_name($insert_stmt, ":szoveg", $szoveg);
    oci_bind_by_name($insert_stmt, ":felhasznalonev", $felhasznalonev);
    oci_bind_by_name($insert_stmt, ":bejegyzes_id", $bejegyzes_id, -1, SQLT_INT);
    
    if (!oci_execute($insert_stmt)) {
        $error = oci_error($insert_stmt);
        throw new Exception("Bejegyzés létrehozása sikertelen: " . $error['message']);
    }
    
    oci_free_statement($insert_stmt);
    
    // Csoporthoz rendelés
    $link_sql = "INSERT INTO CSOPORT_BEJEGYZES (BEJEGYZES_ID, CSOPORT_ID) 
                 VALUES (:bejegyzes_id, :csoport_id)";
    $link_stmt = oci_parse($conn, $link_sql);
    oci_bind_by_name($link_stmt, ":bejegyzes_id", $bejegyzes_id);
    oci_bind_by_name($link_stmt, ":csoport_id", $csoport_id);
    
    if (!oci_execute($link_stmt)) {
        $error = oci_error($link_stmt);
        throw new Exception("Bejegyzés csoporthoz rendelése sikertelen: " . $error['message']);
    }
    
    oci_free_statement($link_stmt);
    
    // Képfeltöltés kezelése (ha van)
    if (isset($_FILES['kep']) && $_FILES['kep']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['kep']['tmp_name']);
        $imageType = $_FILES['kep']['type'];
        
        $blob_sql = "INSERT INTO KEPEK (KEP_ID, FOTO, BEJEGYZES_ID) 
                     VALUES (kep_id_seq.NEXTVAL, EMPTY_BLOB(), :bejegyzes_id) 
                     RETURNING FOTO INTO :imageData";
        $blob_stmt = oci_parse($conn, $blob_sql);
        
        $blob = oci_new_descriptor($conn, OCI_D_LOB);
        oci_bind_by_name($blob_stmt, ":imageData", $blob, -1, OCI_B_BLOB);
        oci_bind_by_name($blob_stmt, ":bejegyzes_id", $bejegyzes_id);
        
        if (!oci_execute($blob_stmt, OCI_NO_AUTO_COMMIT)) {
            $error = oci_error($blob_stmt);
            throw new Exception("Képfeltöltés sikertelen: " . $error['message']);
        }
        
        if (!$blob->save($imageData)) {
            throw new Exception("Kép mentése sikertelen");
        }
        
        $blob->free();
        oci_free_statement($blob_stmt);
    }
    
    // Tranzakció commit
    oci_execute(oci_parse($conn, "COMMIT"));
    header("Location: ../csoport_reszletek.php?id=" . $csoport_id . "&success=1");
    exit;
    
} catch (Exception $e) {
    oci_execute(oci_parse($conn, "ROLLBACK"));
    error_log("Hiba: " . $e->getMessage());
    header("Location: ../csoport_reszletek.php?id=" . $csoport_id . "&error=" . urlencode($e->getMessage()));
    exit;
}