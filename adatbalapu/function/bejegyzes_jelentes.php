<?php
include __DIR__ . "/database_contr.php";
session_start();

header('Content-Type: application/json');

// Enable detailed error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Authentication check
if (!isset($_SESSION["felhasznalo"])) {
    echo json_encode(['success' => false, 'error' => 'Hozzáférés megtagadva! Jelentkezz be.']);
    exit;
}

// Input validation
$required = ['bejegyzes_id', 'jelentett_felhasznalo', 'tipus', 'leiras'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'error' => 'Hiányzó adatok: ' . $field]);
        exit;
    }
}

$bejegyzes_id = $_POST['bejegyzes_id'];
$jelentett_felhasznalo = $_POST['jelentett_felhasznalo'];
$tipus = $_POST['tipus'];
$leiras = $_POST['leiras'];
$felhasznalonev = $_SESSION["felhasznalo"]["felhasználónév"];

// Self-report check
if ($jelentett_felhasznalo === $felhasznalonev) {
    echo json_encode(['success' => false, 'error' => 'Saját magadat nem jelented!']);
    exit;
}

// Database connection
try {
    $conn = adatb_betoltes();
    if (!$conn) {
        throw new Exception("Adatbázis kapcsolódási hiba");
    }

    // Prepare SQL
    $sql = "INSERT INTO JELENTES (
                JELENTES_ID, 
                TIPUS, 
                JELENTETT, 
                STATUSZ, 
                LETREHOZAS_DATUMA, 
                LEIRAS, 
                ADMINNEV, 
                FELHASZNALONEV
            ) VALUES (
                (SELECT NVL(MAX(JELENTES_ID),0)+1 FROM JELENTES), 
                :tipus, 
                :jelentett, 
                'Feldolgozás alatt', 
                SYSDATE, 
                :leiras, 
                (SELECT ADMINNEV FROM ADMIN WHERE ROWNUM = 1), 
                :felhasznalonev
            )";
    
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":tipus", $tipus);
    oci_bind_by_name($stmt, ":jelentett", $jelentett_felhasznalo);
    oci_bind_by_name($stmt, ":leiras", $leiras);
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);
    
    if (!oci_execute($stmt)) {
        $e = oci_error($stmt);
        throw new Exception("Oracle hiba: " . $e['message']);
    }
    
    oci_free_statement($stmt);
    oci_commit($conn);
    
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    file_put_contents('jelentes_error.log', date('Y-m-d H:i:s') . " - Hiba: " . $e->getMessage() . "\n", FILE_APPEND);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    
} finally {
    if (isset($conn) && $conn) {
        oci_close($conn);
    }
}
?>