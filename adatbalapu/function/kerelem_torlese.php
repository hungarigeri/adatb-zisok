<?php
include "database_contr.php";
session_start();

$response = ['success' => false, 'message' => ''];

try {
    if (!isset($_SESSION["felhasznalo"])) {
        throw new Exception('Bejelentkezés szükséges');
    }

    if (!isset($_POST['kerelem_id']) || !is_numeric($_POST['kerelem_id'])) {
        throw new Exception('Érvénytelen kérelem azonosító');
    }

    $felhasznalonev = $_SESSION["felhasznalo"]["felhasználónév"];
    $kerelem_id = (int)$_POST['kerelem_id'];

    if (baratjeloles_torlese($kerelem_id, $felhasznalonev)) {
        $response = ['success' => true, 'message' => 'Barátkérelem sikeresen törölve'];
    } else {
        throw new Exception('Sikertelen törlés');
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;