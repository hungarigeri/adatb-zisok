<?php
include __DIR__ . "/database_contr.php";
session_start();

if(!isset($_SESSION["felhasznalo"])){
    header("Location: /index.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modosit'])) {
    $csoport_id = $_POST['csoport_id'];
    $uj_nev = trim($_POST['nev']);
    $uj_leiras = trim($_POST['leiras']);
    $uj_tulajdonos = isset($_POST['uj_tulajdonos']) ? trim($_POST['uj_tulajdonos']) : null;
    $felhasznalonev = $_SESSION["felhasznalo"]["felhasználónév"];

    $conn = adatb_betoltes();
    if (!$conn) {
        $_SESSION['error'] = "Adatbázis kapcsolódási hiba";
        header("Location: /csoportok.php");
        exit();
    }

    // Start transaction
    $begin_stmt = oci_parse($conn, "BEGIN");
    if (!oci_execute($begin_stmt)) {
        oci_close($conn);
        $_SESSION['error'] = "Tranzakció indítása sikertelen";
        header("Location: /csoportok.php");
        exit();
    }

    try {
        // 1. Update group info
        $sql = "UPDATE CSOPORT SET NEV = :nev, LEIRAS = :leiras WHERE CSOPORT_ID = :id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":nev", $uj_nev);
        oci_bind_by_name($stmt, ":leiras", $uj_leiras);
        oci_bind_by_name($stmt, ":id", $csoport_id);

        if (!oci_execute($stmt)) {
            throw new Exception("Csoport módosítása sikertelen");
        }

        // 2. If new owner is specified, transfer ownership
        if ($uj_tulajdonos && $uj_tulajdonos !== '') {
            // First delete current manager
            $sql_delete = "DELETE FROM CSOPORTOT_KEZEL 
                          WHERE CSOPORT_ID = :id AND FELHASZNALONEV = :current_user";
            $stmt_delete = oci_parse($conn, $sql_delete);
            oci_bind_by_name($stmt_delete, ":id", $csoport_id);
            oci_bind_by_name($stmt_delete, ":current_user", $felhasznalonev);

            if (!oci_execute($stmt_delete)) {
                throw new Exception("Jelenlegi tulajdonos eltávolítása sikertelen");
            }

            // Then add new manager
            $sql_insert = "INSERT INTO CSOPORTOT_KEZEL (FELHASZNALONEV, CSOPORT_ID)
                           VALUES (:new_user, :id)";
            $stmt_insert = oci_parse($conn, $sql_insert);
            oci_bind_by_name($stmt_insert, ":new_user", $uj_tulajdonos);
            oci_bind_by_name($stmt_insert, ":id", $csoport_id);

            if (!oci_execute($stmt_insert)) {
                throw new Exception("Új tulajdonos hozzáadása sikertelen");
            }
        }

        // Commit transaction
        $commit_stmt = oci_parse($conn, "COMMIT");
        if (!oci_execute($commit_stmt)) {
            throw new Exception("Tranzakció végrehajtása sikertelen");
        }
        
        header("Location: /csoportok.php?updated=1");
        exit;
    } catch (Exception $e) {
        // Rollback on error
        $rollback_stmt = oci_parse($conn, "ROLLBACK");
        oci_execute($rollback_stmt);
        $_SESSION['error'] = "Hiba történt: " . $e->getMessage();
        header("Location: /csoportok.php");
        exit;
    } finally {
        if (isset($stmt)) oci_free_statement($stmt);
        if (isset($stmt_delete)) oci_free_statement($stmt_delete);
        if (isset($stmt_insert)) oci_free_statement($stmt_insert);
        oci_close($conn);
    }
} else {
    header("Location: /csoportok.php");
    exit;
}