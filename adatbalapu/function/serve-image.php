<?php
include "function/database_contr.php";

if (isset($_GET['kep_id'])) {
    $kep_id = $_GET['kep_id'];
    $conn = adatb_betoltes();
    $sql = "SELECT * FROM KEPEK WHERE KEP_ID = :kep_id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":kep_id", $kep_id);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        http_response_code(500);
        echo "Adatbázis hiba: " . $error['message'];
        exit;
    }

    if ($row = oci_fetch_assoc($stmt)) {
        // Beállítjuk a tartalom típusát
        $mime_type = !empty($row['MIME_TYPE']) ? $row['MIME_TYPE'] : "image/png";
        header("Content-Type: " . $mime_type);

        // A BLOB adat kiírása
        echo $row['FOTO'];
    } else {
        http_response_code(404);
        echo "Kép nem található.";
    }

    oci_free_statement($stmt);
    oci_close($conn);
} else {
    http_response_code(400);
    echo "Hibás kérés.";
}
?>