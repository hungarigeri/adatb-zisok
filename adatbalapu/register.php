<?php
include "function/database_contr.php";
session_start();

ini_set('display_errors', 0); // Ne jelenjenek meg a hibák a képernyőn
if(isset($_SESSION["felhasznalo"])){
    header("Location: index.php");
}

$hibak=array();
if(isset($_POST["regisztral"])){
    if(!isset($_POST["nev"]) || trim($_POST["nev"])==="" || !isset($_POST["felhasznalonev"]) || trim($_POST["felhasznalonev"])==="" ||
        !isset($_POST["email"]) || trim($_POST["email"])==="" || !isset($_POST["jelszo"]) || trim($_POST["jelszo"])==="" || !isset($_POST["jelszo2"]) || trim($_POST["jelszo2"])===""){
        $hibak[]="Hiányzó adat!";
    }

    if(!preg_match('/^[a-z0-9.-]+@([a-z0-9-]+\.)+[a-z]{2,4}$/',$_POST["email"]))
        $hibak[] = "Az email formátuma nem megfelelő!";


    if(trim($_POST["nevnap"]) !== "" && !preg_match('/^(0[1-9]|1[0-2])\.(0[1-9]|[12][0-9]|3[01])\.$/',$_POST["nevnap"])){
        $hibak[] = "A névnap formátuma nem megfelelő!";
    }

    $felhasznalonev=$_POST["felhasznalonev"];
    $nev=$_POST["nev"];
    $email=$_POST["email"];
    $jelszo=$_POST["jelszo"];
    $ellenorzo=$_POST["jelszo2"];
    $szulinap = $_POST["birthday"];
    $nevnap=$_POST["nevnap"];

    if($jelszo!==$ellenorzo){
        $hibak[]="A jelszó és az ellenőrző jelszó nem egyezik!";
    }

    if(foglalt_felhasznalonev($felhasznalonev)){
        $hibak[]="Foglalt felhasználónév!";
    }

    if(foglalt_email($email)){
        $hibak[]="Foglalt email!";
    }

    if (count($hibak) === 0) {
        $jelszo = password_hash($jelszo, PASSWORD_DEFAULT);
    
        try {
            felhasznalo_mentes($felhasznalonev, $nev, $email, $jelszo, $nevnap, $szulinap);
            header("Location: login.php");
        } catch (PDOException $e) {
            // Ellenőrizzük, hogy a trigger által generált hibáról van-e szó
            if ($e->getCode() == 20001) {
                header("Location: register.php"); // A trigger által generált hibaüzenet
            } else {
                $hibak[] = "Adatbázis hiba történt: " . $e->getMessage();
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="style/alap.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/png" href="kepek/logo_no_background.png">
    <link href="https://fonts.googleapis.com/css2?family=Barriecito&family=Palanquin+Dark:wght@400;500;600;700&family=Roboto:ital,wght@0,100..900;1,100..900&family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">


</head>
<body>


<nav>
    <a href="index.php">Főoldal</a>
    <div class="fiok">
        <a href="login.php">Bejelentkezés</a>
    </div>
</nav>


<h2>Regisztráció</h2>
<form action="register.php" method="post">
    <label>
        <b>Név:</b>
        <input type="text" name="nev" placeholder="Nagy Ferenc" required>
    </label>
    <label>
        <b>Felhasználónév:</b>
        <input type="text" name="felhasznalonev" placeholder="ferike42" required>
    </label>
    <label>
        <b>Email:</b>
        <input type="text" name="email" placeholder="ferenc@gmail.com" required>
    </label>
    <label>
        <b>Születésnap:</b>
        <input type="date" name="birthday">
    </label>
    <label>
        <b>Névnap:</b>
        <input type="text" name="nevnap" placeholder="02.24.">
    </label>
    <label>
        <b>Jelszó:</b>
        <input type="password" name="jelszo" placeholder="*********" required>
    </label>
    <label>
        <b>Jelszó ismét:</b>
        <input type="password" name="jelszo2" placeholder="*********" required>
    </label>
    <input type="submit" name="regisztral" value="Regisztrálok!">
</form>
<?php if (!empty($hibak)): ?>

    <div class="hiba">
        <?php foreach ($hibak as $hiba): ?>
            
        <?php endforeach; ?>
    </div>
<?php endif; ?>
</body>
</html>