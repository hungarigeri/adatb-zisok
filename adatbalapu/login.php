<?php
include "function/database_contr.php";
session_start();
if(isset($_SESSION["felhasznalo"])){
    header("Location: index.php");
}

$uzenet="";

if(isset($_POST["bejelentkezik"])){
    if(!isset($_POST["felhasznalonev"]) || trim($_POST["felhasznalonev"])==="" || !isset($_POST["jelszo"]) || trim($_POST["jelszo"])===""){
        $uzenet="Adja meg mindkét adatot!";
    }else{
        $felhasznalonev=$_POST["felhasznalonev"];
        $jelszo=$_POST["jelszo"];

        $felhasznalo=felhasznalo_adatok($felhasznalonev);

        if(count($felhasznalo)!==0 && password_verify($jelszo, $felhasznalo["jelszó"])){
            $_SESSION["felhasznalo"]=felhasznalo_adatok($felhasznalonev);
            $_SESSION["felhasznalo"]["admin-e"]=admin_e($felhasznalonev);
            $_SESSION['ismeros_kerelem'] = felhasznalo_baratkerelemek($felhasznalonev);
            allapotfrissites($felhasznalonev);
            header("Location: index.php");
        }

        if(!isset($_SESSION["felhasznalo"])){
            $uzenet="Sikertelen belépés! A belépési adatok nem megfelelők!";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Bejelentkezés</title>
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
        <a href="register.php">Regisztráció</a>
    </div>
</nav>


<h2>Bejelentkezés</h2>
<form action="login.php" method="post">
    <label>
        <b>Felhasználónév:</b>
        <input type="text" name="felhasznalonev" placeholder="ferike42" required>
    </label>
    <label>
        <b>Jelszó:</b>
        <input type="password" name="jelszo" placeholder="*********" required>
    </label>
    <input type="submit" name="bejelentkezik" value="Bejelentkezés">
</form>
<?php
echo "<p class='hiba'>" . $uzenet ."</p>";
echo "</br>";
?>
</body>
</html>

