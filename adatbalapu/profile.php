<?php
include "function/database_contr.php";
include "function/dateformatter.php";
session_start();
if(!isset($_SESSION["felhasznalo"])){
    header("Location: index.php");
}

$felhasznalonev=$_SESSION["felhasznalo"]["felhasználónév"];
$hiba="";


if (isset($_POST["kep_feltoltes"])) {
    if (isset($_FILES['kep']) && $_FILES['kep']['error'] === 0) {
        if ($_FILES['kep']['size'] > 5 * 1024 * 1024) {
            $hiba = "A kép túl nagy! (max 5MB)";
        }
        elseif (!in_array($_FILES['kep']['type'], ['image/jpeg', 'image/png'])) {
            $hiba = "Csak JPG, vagy PNG lehet a kép!";
        } else {
            $kep_tartalom = file_get_contents($_FILES['kep']['tmp_name']);
            profilkep_feltoltes($kep_tartalom,$felhasznalonev,$_SESSION["felhasznalo"]["admin-e"]);
        }
    } else {
        $hiba = "Töltsön fel egy képet!";
    }
}
function felhasznalo_lajkok_szama($felhasznalonev) {
    $conn = adatb_betoltes();
    $sql = "SELECT SUM((SELECT COUNT(*) FROM LAJKOLAS l WHERE l.BEJEGYZES_ID = b.BEJEGYZES_ID)) AS OSSZES_LAJK
            FROM BEJEGYZES b
            WHERE b.FELHASZNALONEV = :felhasznalonev";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
    oci_close($conn);
    return $row["OSSZES_LAJK"];
}
function felhasznalo_bejegyzes_lajkok($felhasznalonev) {
    $conn = adatb_betoltes();
    $sql = "SELECT b.BEJEGYZES_ID, b.LEIRAS, COUNT(l.FELHASZNALONEV) AS LAJKOK_SZAMA
            FROM BEJEGYZES b
            LEFT JOIN LAJKOLAS l ON b.BEJEGYZES_ID = l.BEJEGYZES_ID
            WHERE b.FELHASZNALONEV = :felhasznalonev
            GROUP BY b.BEJEGYZES_ID, b.LEIRAS
            ORDER BY LAJKOK_SZAMA DESC";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);
    oci_execute($stmt);

    $lajkok = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $lajkok[] = [
            'BEJEGYZES_ID' => $row['BEJEGYZES_ID'],
            'LEIRAS' => $row['LEIRAS'],
            'LAJKOK_SZAMA' => $row['LAJKOK_SZAMA']
        ];
    }

    oci_free_statement($stmt);
    oci_close($conn);

    return $lajkok;
}

?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
    <link rel="stylesheet" href="style/alap.css">
    <link rel="stylesheet" href="style/baratkerelem.css">
    <link rel="icon" type="image/png" href="kepek/logo_no_background.png">


    <script>
function kerelemTorlese(kerelem_id) {
    if (confirm('Biztosan törölni szeretnéd ezt a barátkérelmet?')) {
        fetch('function/kerelem_torlese.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'kerelem_id=' + encodeURIComponent(kerelem_id)
        })
        .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Frissítjük a felhasználó listát
                    location.reload();
                } else {
                    alert('Hiba: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Hiba:', error);
                alert('Hiba történt a jelölés küldése közben');
            });
        }
    }
    function kerelemElfogadasa(kerelem_id, felhasznalonev) {
    if (confirm('Biztosan elfogadod ezt a barátkérelmet?')) {
        fetch('function/baratjeloles_elfogadasa.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'kerelem_id=' + encodeURIComponent(kerelem_id) + '&felhasznalonev=' + encodeURIComponent(felhasznalonev)
        })
        .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Frissítjük a felhasználó listát
                    location.reload();
                } else {
                    alert('Hiba: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Hiba:', error);
                alert('Hiba történt a jelölés küldése közben');
            });
        }
}
// Popup kezelése
function showPopup(uzenet) {
    document.getElementById('popup-uzenet').innerHTML = uzenet;
    document.getElementById('rendszeruzenet-popup').style.display = 'flex';
}

function closePopup() {
    document.getElementById('rendszeruzenet-popup').style.display = 'none';
}

// Az oldal betöltésekor ellenőrizzük, vannak-e új rendszerüzenetek
document.addEventListener('DOMContentLoaded', function() {
    <?php
    $uzenetek = get_rendszeruzenetek($felhasznalonev);
    if (!empty($uzenetek)) {
        // Csak a legújabb üzenetet jelenítjük meg
        $legujabb = $uzenetek[0];
        echo "showPopup('" . addslashes($legujabb['SZOVEG']) . "');";
    }


    function osszes_jelentes() {
        $conn = adatb_betoltes();
        $sql = "SELECT j.JELENTES_ID, j.LEIRAS, j.LETREHOZAS_DATUMA, 
                       j.STATUSZ as ALLAPOT, j.TIPUS as TARGY_TIPUS, j.JELENTETT,
                       j.FELHASZNALONEV as JELENTO
                FROM JELENTES j
                ORDER BY j.LETREHOZAS_DATUMA DESC";
        $stmt = oci_parse($conn, $sql);
        oci_execute($stmt);
    
        $jelentesek = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $jelentesek[] = $row;
        }
    
        oci_free_statement($stmt);
        oci_close($conn);
    
        return $jelentesek;
    }
    
    function jelentest_kezel($jelentes_id, $action) {
        $conn = adatb_betoltes();
        $statusz = ($action == 'accept') ? 'Elfogadva' : 'Elutasítva';
        
        $sql = "UPDATE JELENTES SET STATUSZ = :statusz WHERE JELENTES_ID = :jelentes_id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":statusz", $statusz);
        oci_bind_by_name($stmt, ":jelentes_id", $jelentes_id);
        
        $success = oci_execute($stmt);
        oci_free_statement($stmt);
        oci_close($conn);
        
        return $success;
    }
    
    // Jelentés kezelése POST kérés esetén
    if ($_SESSION['felhasznalo']['admin-e'] && isset($_POST['jelentes_action'])) {
        $jelentes_id = $_POST['jelentes_id'];
        $action = $_POST['jelentes_action'];
        
        if (jelentest_kezel($jelentes_id, $action)) {
            $hiba = "A jelentés sikeresen " . ($action == 'accept' ? "elfogadva" : "elutasítva");
        } else {
            $hiba = "Hiba történt a jelentés kezelése közben";
        }
    }
    ?>
});

</script>
</head>
<body>
<div id="rendszeruzenet-popup" class="popup-overlay">
    <div class="popup-content">
        <span class="popup-close" onclick="closePopup()">&times;</span>
        <h3>Rendszerüzenet</h3>
        <div id="popup-uzenet"></div>
    </div>
</div>
 
<nav>
    <div class="fiok">
        <a href="index.php">Főoldal</a>
        <a href="szulinapok.php">Születésnapok</a>
        <a href="cseveges.php">Csevegés</a>
        <a href="csoportok.php">Csoportok</a>
        <a href="function/logout.php">Kijelentkezés</a>
    </div>
</nav>

<div id="fb">
  <div id="fb-top">
    <p><b>Barátkérelmeid <a href="http://">Keress barátokat</a></b></p>
  </div>
  <?php
$kerelemek = felhasznalo_baratkerelemek($felhasznalonev);
if(!empty($kerelemek)) {
    foreach($kerelemek as $kerelem) {
        echo "<div class='kerelem-box'>";
        echo "<img src='kepek/stock.jpg' alt='Image'>";
        echo "<div id='info'>";
        echo "Név: ". $kerelem['FELHASZNALONEV']. "<br>";
        echo "Állapot: " . $kerelem['ALLAPOT'] . "<br>";
        echo "Dátum: " . formatdate($kerelem['LETREHOZAS_DATUMA']) . "<br><br>";
        echo "</div>";
        echo "<div id='button-block'>";
        echo "<div id='confirm' onclick='kerelemElfogadasa(\"".$kerelem['ISMEROS_KERELEM_ID']."\", \"".$felhasznalonev."\")'>Confirm</div>";
        echo "<div id='delete' onclick='kerelemTorlese(\"".$kerelem['ISMEROS_KERELEM_ID']."\")'>Delete Request</div>";
        echo "</div>";
        echo "</div>"; 
    }
} else {
    echo "Nincsenek barátkérelmeid.";
}
?>
</div>
<h2><?php
    echo ($_SESSION['felhasznalo']['felhasználónév']).($_SESSION['felhasznalo']['admin-e'] ? " (admin)":"");
    ?>
</h2>
<div id="profilinfo">
    <form action="profile.php" method="post" enctype="multipart/form-data">
        <label for="kep">Profilkép feltöltése:</label>
        <input type="file" name="kep" id="kep">
        <input type="submit" name="kep_feltoltes" value="Kép feltöltése">
    </form>
    <p><b>Név:</b> <?php echo $_SESSION['felhasznalo']['név']; ?></p>
    <p><b>Email:</b> <?php echo $_SESSION['felhasznalo']['email']; ?></p>
    <p><b>Születésnap:</b> <?php
        if(trim($_SESSION['felhasznalo']['szülinap'])!==""){
            echo formatdate($_SESSION['felhasznalo']['szülinap']);
        }else{
            echo "Nincs megadva.";
        } ?></p>
    <p><b>Névnap:</b> <?php
        if(trim($_SESSION['felhasznalo']['névnap'])!==""){
            echo $_SESSION['felhasznalo']['névnap'];
        }else{
            echo "Nincs megadva.";
        }?></p>
    <p><b>Csatlakozás dátuma:</b> <?php echo formatdate($_SESSION['felhasznalo']['regdátum']); ?></p>
    <p><b>Ismerősök száma:</b> <?php echo $_SESSION['felhasznalo']['ismerősszám']; ?></p>
    <p><b>Lájkolt bejegyzések:</b> <?php echo felhasznalo_lajkok_szama($felhasznalonev); ?></p>
    <p><b>Leglájkoltabb bejegyzés:</b></p>
<?php
$bejegyzes_lajkok = felhasznalo_bejegyzes_lajkok($felhasznalonev);
if (!empty($bejegyzes_lajkok)) {
    // Az első elem lekérése
    $leglajkoltabb = $bejegyzes_lajkok[0]; // Az első elem a leglájkoltabb
    $leglajkoltabb_leiras = $leglajkoltabb['LEIRAS'];
    $leglajkoltabb_lajkok = $leglajkoltabb['LAJKOK_SZAMA'];
    echo "$leglajkoltabb_leiras - $leglajkoltabb_lajkok lájk</p>";
}
 else {
    echo "<p>Nincs bejegyzésed.</p>";
}
?>   
    <a class="fiok_gomb_bejegyzesek" href="bejegyzesek.php">Bejegyzéseim</a>
    <div id="gombok">
        <a class="fiok_gomb" href="modifyprofile.php">Módosítás</a>
        <a class="fiok_gomb" href="function/account_delete.php">Fiók törlése</a>
    </div>
</div>

<div id="jelentesek-section">
    <h3>Általam létrehozott jelentések</h3>
    <?php
    $jelentesek = felhasznalo_jelentesei($felhasznalonev);
    if (!empty($jelentesek)) {
        echo '<table>';
        echo '<tr><th>Tárgy típusa</th><th>Leírás</th><th>Dátum</th><th>Állapot</th></tr>';
        foreach ($jelentesek as $jelentes) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($jelentes['TARGY_TIPUS']) . '</td>';
            echo '<td>' . htmlspecialchars($jelentes['LEIRAS']) . '</td>';
            echo '<td>' . formatdate($jelentes['DATUM']) . '</td>';
            echo '<td>' . htmlspecialchars($jelentes['ALLAPOT']) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>Még nem hoztál létre jelentéseket.</p>';
    }
    ?>
</div>
<?php if ($_SESSION['felhasznalo']['admin-e']): ?>
<div id="admin-jelentesek-section">
    <h3>Összes jelentés (Admin felület)</h3>
    <?php
    $osszes_jelentes = osszes_jelentes();
    if (!empty($osszes_jelentes)) {
        echo '<table class="admin-table">';
        echo '<tr>
                <th>Jelentő</th>
                <th>Tárgy típusa</th>
                <th>Leírás</th>
                <th>Jelentett felhasználó</th>
                <th>Dátum</th>
                <th>Állapot</th>
                <th>Műveletek</th>
              </tr>';
        
        foreach ($osszes_jelentes as $jelentes) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($jelentes['JELENTO']) . '</td>';
            echo '<td>' . htmlspecialchars($jelentes['TARGY_TIPUS']) . '</td>';
            echo '<td>' . htmlspecialchars($jelentes['LEIRAS']) . '</td>';
            echo '<td>' . htmlspecialchars($jelentes['JELENTETT']) . '</td>';
            echo '<td>' . formatdate($jelentes['LETREHOZAS_DATUMA']) . '</td>';
            echo '<td>' . htmlspecialchars($jelentes['ALLAPOT']) . '</td>';
            
            // Művelet gombok csak ha nincs még kezelve
            if ($jelentes['ALLAPOT'] == 'Feldolgozás alatt') {
                echo '<td class="action-buttons">
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="jelentes_id" value="' . $jelentes['JELENTES_ID'] . '">
                            <input type="hidden" name="jelentes_action" value="accept">
                            <button type="submit" class="accept-btn">Elfogad</button>
                        </form>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="jelentes_id" value="' . $jelentes['JELENTES_ID'] . '">
                            <input type="hidden" name="jelentes_action" value="reject">
                            <button type="submit" class="reject-btn">Elutasít</button>
                        </form>
                      </td>';
            } else {
                echo '<td>Kezelve</td>';
            }
            
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>Nincsenek jelentések.</p>';
    }
    ?>
</div>
<?php endif; ?>
</body>
</html>
