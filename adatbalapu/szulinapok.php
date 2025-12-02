<?php
include "function/statisztikak.php";
include "function/dateformatter.php";

session_start();
if(!isset($_SESSION["felhasznalo"])){
    header("Location: index.php");
}

$felhasznalonev=$_SESSION["felhasznalo"]["felhasználónév"];
$szuletesnapok = ismerosok_szuletesnapjai($felhasznalonev);


?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Születésnapok</title>
    <link rel="stylesheet" href="style/alap.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/png" href="kepek/logo_no_background.png">
    <link href="https://fonts.googleapis.com/css2?family=Barriecito&family=Palanquin+Dark:wght@400;500;600;700&family=Roboto:ital,wght@0,100..900;1,100..900&family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
<nav>
    <div class="fiok">
        <a href="index.php">Főoldal</a>
        <a href="profile.php">Fiók</a>
        <a href="cseveges.php">Csevegés</a>
        <a href="csoportok.php">Csoportok</a>
        <a href="function/logout.php">Kijelentkezés</a>
    </div>
</nav>


<h2>Születésnapok</h2>

<?php if ($szuletesnapok && count($szuletesnapok) > 0): ?>
    <table>
        <tr>
            <th>Hónap</th>
            <th>Születésnapok száma</th>
        </tr>
        <?php foreach ($szuletesnapok as $sor):
            $szuletesnaposok=honap_szulinapok($felhasznalonev,trim($sor['HONAP']));
            ?>
            <tr>
                <td><?= htmlspecialchars($sor['HONAP']) ?></td>
                <td><?= htmlspecialchars($sor['SZULETESNAPOK']) ?></td>
            </tr>
                <?php foreach ($szuletesnaposok as $szuletesnapos): ?>
            <tr>
                <td colspan="2"><?= htmlspecialchars($szuletesnapos['FELHASZNALONEV']) .", ". formatdate(htmlspecialchars($szuletesnapos['SZULINAP'])) ?></td>
            </tr>
                <?php endforeach; ?>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Nincs születésnapos ismerős.</p>
<?php endif; ?>
</body>
</html>
