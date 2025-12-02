<?php
include "../function/database_contr.php";
session_start();

if(!isset($_SESSION["felhasznalo"])){
    header("Location: ../index.php");
    exit();
}

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $csoport_id = $_GET['id'];
    $felhasznalonev = $_SESSION["felhasznalo"]["felhasználónév"];
    
    if(function_exists('csoport_csatlakozas')) {
        if(csoport_csatlakozas($felhasznalonev, $csoport_id)) {
            header("Location: ../csoport_reszletek.php?id=" . $csoport_id);
        } else {
            $error = oci_error();
            error_log("Group join failed: " . $error['message']);
            header("Location: ../csoportok.php?error=csatlakozas_hiba&details=" . urlencode($error['message']));
        }
    } else {
        error_log("A csoport_csatlakozas függvény nem található");
        header("Location: ../csoportok.php?error=fuggveny_hiba");
    }
} else {
    header("Location: ../csoportok.php?error=ervenytelen_id");
}
exit();
