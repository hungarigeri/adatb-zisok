<?php
include "database_contr.php";
session_start();

if (!isset($_SESSION["felhasznalo"])) {
    die("Hozzáférés megtagadva!");
}

if (!isset($_POST['partner'])) {
    die("Hiányzó partner adatok!");
}

$felhasznalonev = $_SESSION["felhasznalo"]["felhasználónév"];
$partner = $_POST['partner'];

$uzenetek = beszelgetes_uzenetek($felhasznalonev, $partner);

// Partner adatainak lekérdezése
$partnerAdatok = felhasznalo_adatok($partner);

// Üzenetek megjelenítése
echo '<div class="beszelgetes-fejlec" data-partner="' . htmlspecialchars($partner) . '">';
echo '<h3>Beszélgetés ' . htmlspecialchars($partnerAdatok['név']) . '-val/-vel</h3>';
echo '</div>';

if ($uzenetek) {
    foreach ($uzenetek as $uzenet) {
        $osztaly = ($uzenet['FELHASZNALONEV'] == $felhasznalonev) ? 'sajat-uzenet' : 'partner-uzenet';
        echo '<div class="uzenet ' . $osztaly . '" id="uzenet-' . $uzenet['UZENET_ID'] . '">';
        echo '<p class="uzenet-szoveg">' . htmlspecialchars($uzenet['SZOVEG']) . '</p>';
        echo '<span class="uzenet-datum">' . $uzenet['KULDES_DATUMA'] . '</span>';
        
        // Csak a saját üzenetekhez adjuk hozzá a törlés és módosítás gombot
        if ($uzenet['FELHASZNALONEV'] == $felhasznalonev) {
            echo '<button class="delete" onclick="uzenetTorlese('.$uzenet['UZENET_ID'].')">Törlés</button>';
            echo '<button class="modosit" onclick="uzenetModositasa('.$uzenet['UZENET_ID'].', \''.htmlspecialchars($uzenet['SZOVEG'], ENT_QUOTES).'\')">Módosít</button>';
        }
        
        echo '</div>';
    }
} else {
    echo '<p>Még nincsenek üzenetek ezzel a partnerrel.</p>';
}
?>