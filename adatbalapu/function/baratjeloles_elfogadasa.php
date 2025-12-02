<?php
include "database_contr.php";
session_start();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
    if (!isset($_POST['kerelem_id']) || !isset($_POST['felhasznalonev'])) {
        throw new Exception("Hiányzó adatok");
    }

    $kerelem_id = (int)$_POST['kerelem_id'];
    $felhasznalonev = $_POST['felhasznalonev'];

    if (baratjeloles_elfogadasa($kerelem_id, $felhasznalonev)) {
        felhasznalo_adatok($felhasznalonev);
        $response = [
            'success' => true,
            'message' => 'Barátkérelem sikeresen elfogadva!'
        ];
    } else {
        throw new Exception('Sikertelen elfogadás');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit();
?>