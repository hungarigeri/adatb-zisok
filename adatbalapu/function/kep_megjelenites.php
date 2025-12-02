<?php
include "database_contr.php";

if (!isset($_GET['bejegyzes_id']) || !is_numeric($_GET['bejegyzes_id'])) {
    header("HTTP/1.0 404 Not Found");
    exit;
}

$bejegyzes_id = $_GET['bejegyzes_id'];
$conn = adatb_betoltes();

$sql = "SELECT FOTO FROM KEPEK WHERE BEJEGYZES_ID = :bejegyzes_id";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":bejegyzes_id", $bejegyzes_id);
oci_execute($stmt);

$row = oci_fetch_assoc($stmt);
if ($row && $row['FOTO']) {
    header("Content-Type: image/*");
    echo $row['FOTO']->load();
} else {
    header("HTTP/1.0 404 Not Found");
}

oci_free_statement($stmt);
oci_close($conn);