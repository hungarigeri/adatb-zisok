<?php
include "function/database_contr.php";
session_start();
if(!isset($_SESSION["felhasznalo"])){
    header("Location: index.php");
}

$hibak=array();

if(isset($_POST["frissit"])){
    if(!isset($_POST["nev"]) || trim($_POST["nev"])==="" || !isset($_POST["felhasznalonev"]) || trim($_POST["felhasznalonev"])==="" ||
        !isset($_POST["email"]) || trim($_POST["email"])==="" || !isset($_POST["regi_jelszo"]) || trim($_POST["regi_jelszo"])===""){
        $hibak[]="Hiányzó adat!";
    }

    if(!preg_match('/^[a-z0-9.-]+@([a-z0-9-]+\.)+[a-z]{2,4}$/',$_POST["email"]))
        $hibak[] = "Az email formátuma nem megfelelő!";

    if(trim($_POST["nevnap"]) !== "" && !preg_match( '/^(0[1-9]|1[0-2])\.(0[1-9]|[12][0-9]|3[01])\.$/',$_POST["nevnap"])){
        $hibak[] = "A névnap formátuma nem megfelelő!";
    }

    $regi_felhasznalonev=$_SESSION["felhasznalo"]["felhasználónév"];
    $felhasznalonev=$_POST["felhasznalonev"];
    $nev=$_POST["nev"];
    $email=$_POST["email"];
    $regi_jelszo=$_SESSION["felhasznalo"]["jelszó"];
    $jelszo=$_POST["jelszo"];
    $ellenorzo=$_POST["jelszo2"];
    $szulinap = $_POST["birthday"];
    $nevnap=$_POST["nevnap"];


    if(!password_verify($_POST["regi_jelszo"],$regi_jelszo)){
        $hibak[]= "Nem ez a régi jelszava!";
    }

    if($jelszo!==$ellenorzo){
        $hibak[]="A jelszó és az ellenőrző jelszó nem egyezik meg!";
    }

    if(foglalt_felhasznalonev($felhasznalonev) && $felhasznalonev!==$_SESSION["felhasznalo"]["felhasználónév"]){
        $hibak[]="Foglalt felhasználónév!";
    }

    if(foglalt_email($email) && $email!==$_SESSION["felhasznalo"]["email"]){
        $hibak[]="Foglalt email!";
    }

    if(count($hibak)===0){
        if ($jelszo!=null) $jelszo=password_hash($jelszo,PASSWORD_DEFAULT);
        else $jelszo=$regi_jelszo;

        felhasznalo_modositas($regi_felhasznalonev,$felhasznalonev,$nev,$email,$jelszo,$nevnap,$szulinap,$_SESSION["felhasznalo"]["admin-e"]);
        $_SESSION["felhasznalo"]=felhasznalo_adatok($felhasznalonev);
        $_SESSION["felhasznalo"]["admin-e"]=admin_e($felhasznalonev);
        header("Location: profile.php");
    }
}

?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Módosítás</title>
    <link rel="stylesheet" href="style/alap.css">
    <link rel="icon" type="image/png" href="kepek/logo_no_background.png">
</head>
<body>


<nav>
    <a href="index.php">Főoldal</a>
    <div class="fiok">
        <a href="profile.php">Fiók</a>
        <a href="function/logout.php">Kijelentkezés</a>
    </div>
</nav>


<h2>Adatok változtatása</h2>
<form action="modifyprofile.php" method="post">
    <label>
        <b>Név:</b>
        <input type="text" name="nev" <?php echo 'value="'.$_SESSION["felhasznalo"]["név"].'"'?> " required>
    </label>
    <label>
        <b>Felhasználónév:</b>
        <input type="text" name="felhasznalonev" <?php echo 'value="'.$_SESSION["felhasznalo"]["felhasználónév"].'"'?> required>
    </label>
    <label>
        <b>Email:</b>
        <input type="text" name="email" <?php echo 'value="'.$_SESSION["felhasznalo"]["email"].'"'?> required>
    </label>
    <?php
    $szulinap = $_SESSION["felhasznalo"]["szülinap"];
    $formattedszulinap = date('Y-m-d', strtotime($szulinap));
    ?>
    <label>
        <b>Születésnap:</b>
        <input type="date" name="birthday" <?php echo 'value="'.$formattedszulinap.'"'?>>
    </label>
    <label>
        <b>Névnap:</b>
        <input type="text" name="nevnap" <?php echo 'value="'.$_SESSION["felhasznalo"]["névnap"].'"'?>>
    </label>
    <label>
        <b>Régi jelszó:</b>
        <input type="password" name="regi_jelszo" placeholder="*********" required>
    </label>
    <label>
        <b>Jelszó:</b>
        <input type="password" name="jelszo" placeholder="*********">
    </label>
    <label>
        <b>Jelszó ismét:</b>
        <input type="password" name="jelszo2" placeholder="*********">
    </label>
    <input type="submit" name="frissit" value="Mentés">
</form>
<?php
foreach ($hibak as $uz){
    echo "<p class='hiba'>".$uz."</p>";
    echo "</br>";
}
?>
</body>
</html>
