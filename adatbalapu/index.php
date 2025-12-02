<?php
include "function/statisztikak.php";


session_start();
if(isset($_SESSION["felhasznalo"])){
$felhasznalonev=$_SESSION["felhasznalo"]["felhasználónév"];
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["like"])) {
        $bejegyzes_id =$_POST["bejegyzes_id"];
        bejegyzes_lajkolasa($felhasznalonev, $bejegyzes_id);
    } elseif (isset($_POST["comment"])) {
        $bejegyzes_id = $_POST["bejegyzes_id"];
        $komment_szoveg = $_POST["komment_szoveg"];
        hozzaszolas_hozzaadasa($bejegyzes_id, $felhasznalonev, $komment_szoveg);
        header("Location: index.php");
    }elseif (isset($_POST["edit_comment"])) {
        $komment_id = $_POST["komment_id"];
        $bejegyzes_id = $_POST["bejegyzes_id"];
        $uj_komment_szoveg = $_POST["uj_komment_szoveg"];
        komment_modositas($komment_id, $uj_komment_szoveg, $felhasznalonev);
        
    }elseif (isset($_POST["delete_comment"])) {
        $komment_id = $_POST["komment_id"];
        komment_torles($komment_id, $felhasznalonev);
        header("Location: index.php");
    }
}
function osszes_bejegyzes_lekerese() {
    $conn = adatb_betoltes();
    $sql = "SELECT b.BEJEGYZES_ID, b.LEIRAS, b.SZOVEG, b.FELTOLTES_IDEJE, b.FELHASZNALONEV, k.FOTO
            FROM BEJEGYZES b
            LEFT JOIN KEPEK k ON b.BEJEGYZES_ID = k.BEJEGYZES_ID
            ORDER BY b.FELTOLTES_IDEJE DESC";
    $stmt = oci_parse($conn, $sql);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        die("Lekérdezési hiba: " . $error['message']);
    }

    $bejegyzesek = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $bejegyzesek[] = $row;
    }

    oci_free_statement($stmt);
    oci_close($conn);

    return $bejegyzesek;
}
function bejegyzes_lajkolasa($felhasznalonev, $bejegyzes_id) {	
    if (!is_numeric($bejegyzes_id)) {
        die("Hiba: A bejegyzés azonosítója nem érvényes szám.");
    }
    $conn = adatb_betoltes();
    // Ellenőrizd, hogy a felhasználó már lájkolta-e a bejegyzést
    $check_sql = 'SELECT COUNT(*) AS COUNT FROM "LAJKOLAS" WHERE  FELHASZNALONEV = :felhasznalonev AND BEJEGYZES_ID = :bejegyzes_id ';
    $check_stmt = oci_parse($conn, $check_sql);
    oci_bind_by_name($check_stmt, ":felhasznalonev", $felhasznalonev);
    oci_bind_by_name($check_stmt, ":bejegyzes_id", $bejegyzes_id, SQLT_INT); // Szám típusú kötés
    oci_execute($check_stmt);
    $row = oci_fetch_assoc($check_stmt);
    $already_liked = $row['COUNT'] > 0;
    oci_free_statement($check_stmt);

    if ($already_liked) {
        // Ha már lájkolta, töröljük a lájkot
        $delete_sql = 'DELETE FROM "LAJKOLAS" WHERE FELHASZNALONEV = :felhasznalonev AND BEJEGYZES_ID = :bejegyzes_id';
        $delete_stmt = oci_parse($conn, $delete_sql);
        oci_bind_by_name($delete_stmt, ":felhasznalonev", $felhasznalonev);
        oci_bind_by_name($delete_stmt, ":bejegyzes_id", $bejegyzes_id, SQLT_INT);

        if (!oci_execute($delete_stmt)) {
            $error = oci_error($delete_stmt);
            die("Lájk eltávolítási hiba: " . $error['message']);
        }

        oci_free_statement($delete_stmt);
    } else {
        // Ha még nem lájkolta, adjuk hozzá a lájkot
        $insert_sql = 'INSERT INTO "LAJKOLAS" (FELHASZNALONEV, BEJEGYZES_ID) VALUES (:felhasznalonev, :bejegyzes_id)';
        $insert_stmt = oci_parse($conn, $insert_sql);
        oci_bind_by_name($insert_stmt, ":felhasznalonev", $felhasznalonev);
        oci_bind_by_name($insert_stmt, ":bejegyzes_id", $bejegyzes_id, SQLT_INT);

        if (!oci_execute($insert_stmt)) {
            $error = oci_error($insert_stmt);
            die("Lájkolási hiba: " . $error['message']);
        }

        oci_free_statement($insert_stmt);
    }
    oci_close($conn);
}
function hozzaszolas_hozzaadasa($bejegyzes_id, $felhasznalonev, $komment_szoveg) {
    $conn = adatb_betoltes();
    $sql = "BEGIN HOZZASZOLAS_HOZZAADAS(:bejegyzes_id, :felhasznalonev, :komment_szoveg); END;";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":bejegyzes_id", $bejegyzes_id);
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);
    oci_bind_by_name($stmt, ":komment_szoveg", $komment_szoveg);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        die("Komment beszúrása sikertelen: " . $error['message']);
    }

    oci_free_statement($stmt);
    oci_close($conn);
}
function kommentek_lekerese($bejegyzes_id) {
    $conn = adatb_betoltes();
    $sql = "SELECT k.KOMMENT_ID, k.KOMMENT, k.LETREHOZAS_DATUMA, k.FELHASZNALONEV
            FROM KOMMENT k
            WHERE k.BEJEGYZES_ID = :bejegyzes_id
            ORDER BY k.LETREHOZAS_DATUMA ASC";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":bejegyzes_id", $bejegyzes_id);
    oci_execute($stmt);
    $kommentek = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $kommentek[] = $row;
    }
    oci_free_statement($stmt);
    oci_close($conn);
    return $kommentek;
}
function komment_modositas($komment_id, $uj_komment_szoveg, $felhasznalonev) {
    $conn = adatb_betoltes();
    $sql = "UPDATE KOMMENT 
            SET KOMMENT = :uj_komment_szoveg 
            WHERE KOMMENT_ID = :komment_id AND FELHASZNALONEV = :felhasznalonev";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":uj_komment_szoveg", $uj_komment_szoveg);
    oci_bind_by_name($stmt, ":komment_id", $komment_id);
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);
    oci_execute($stmt);
    oci_free_statement($stmt);
    oci_close($conn);
}
function komment_torles($komment_id, $felhasznalonev) {
    $conn = adatb_betoltes();
    $sql = "DELETE FROM KOMMENT 
            WHERE KOMMENT_ID = :komment_id AND FELHASZNALONEV = :felhasznalonev";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":komment_id", $komment_id);
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);
    oci_execute($stmt);
    oci_free_statement($stmt);
    oci_close($conn);
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Főoldal</title>
    <link rel="icon" type="image/png" href="kepek/logo_no_background.png">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/alap.css">
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">


    <script>
    function jelolesKuldese(fogadoFelhasznalonev) {
        if (confirm('Biztosan szeretnéd jelölni ezt a felhasználót?')) {
            fetch('function/jeloles_kuldes.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'fogado=' + encodeURIComponent(fogadoFelhasznalonev)
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

    function felhasznaloEltavolitasa(felhasznalonev) {
        // Implementáld az eltávolítási logikát hasonló módon
        console.log("Eltávolítás: " + felhasznalonev);
    }
    </script>
   </head>
<body>
    <nav>
        <div class="fiok">
            <?php
            if(isset($_SESSION["felhasznalo"])) {
                echo '<a href="szulinapok.php">Születésnapok</a>';
                echo '<a href="profile.php">Fiók</a>';
                echo '<a href="cseveges.php">Csevegés</a>';
                
                echo '<a href="csoportok.php">Csoportok</a>';
                echo '<a href="function/logout.php">Kijelentkezés</a>';
            }
            else{
                echo '<a href="register.php">Regisztráció</a>';
                echo '<a href="login.php">Bejelentkezés</a>';
            }
         ?>
        </div>
    </nav>

    <div>
        <p><?php echo "Aktív, jelentés nélküli adminok száma: " . jelentes_nelkuli_adminok();?></p>
    </div>

    <div class="bejegyzesek">
    <h2>Összes bejegyzés</h2>
    <?php
    $bejegyzesek = osszes_bejegyzes_lekerese();
    if (!empty($bejegyzesek)) {
        foreach ($bejegyzesek as $bejegyzes) {
            echo '<div class="bejegyzes">';

            echo '<h3>' . htmlspecialchars($bejegyzes['LEIRAS']) . '</h3>';
            echo '<p>' . nl2br(htmlspecialchars($bejegyzes['SZOVEG'])) . '</p>';
            echo '<small>Feltöltötte: ' . htmlspecialchars($bejegyzes['FELHASZNALONEV']) . ' - ' . htmlspecialchars($bejegyzes['FELTOLTES_IDEJE']) . '</small>';
            
            if (!empty($bejegyzes['FOTO'])) {
                echo '<img src="function/kep_megjelenites.php?bejegyzes_id=' . htmlspecialchars($bejegyzes['BEJEGYZES_ID']) . '" alt="Bejegyzés képe">';
            }

            // Lájkolás gomb
            if (isset($_SESSION["felhasznalo"])) {
                echo '<form action="index.php" method="post" style="display:inline;">';
                echo '<input type="hidden" name="bejegyzes_id" value="' . $bejegyzes['BEJEGYZES_ID'] . '">';
                echo '<button type="submit" name="like">';
                echo '<i class="material-icons">favorite</i>';
                echo '</button>';
                echo '</form>';
                echo '<form action="index.php" method="post">';
                echo '<input type="hidden" name="bejegyzes_id" value="' . $bejegyzes['BEJEGYZES_ID'] . '">';
                echo '<textarea name="komment_szoveg" rows="2" placeholder="Írj egy hozzászólást..." required></textarea>';
                echo '<button type="submit" name="comment"><i class="material-icons">send</i></button>';
                echo '</form>';
                
                // Hozzászólások megjelenítése
                $kommentek = kommentek_lekerese($bejegyzes['BEJEGYZES_ID']);
                if (!empty($kommentek)) {
                    echo '<div class="hozzaszolasok">';
                    foreach ($kommentek as $komment) {
                        if ($komment['FELHASZNALONEV'] === $felhasznalonev){
                            echo '<form  class="edit-comment-form" action="index.php" method="post" style="display:inline;">';
                            echo '<textarea name="uj_komment_szoveg" rows="3" required>' . htmlspecialchars($komment["KOMMENT"]) . '</textarea>';
                            echo '<input type="hidden" name="komment_id" value="' . $komment['KOMMENT_ID'] . '">';
                            echo '<input type="hidden" name="bejegyzes_id" value="' . $bejegyzes['BEJEGYZES_ID'] . '">';
                            echo '<button type="submit" name="edit_comment"><i class="material-icons">edit</i></button>';
                            echo '</form>';
                            echo '<form action="index.php" method="post" style="display:inline;">';
                            echo '<input type="hidden" name="komment_id" value="' . $komment['KOMMENT_ID'] . '">';
                            echo '<button type="submit" name="delete_comment"><i class="material-icons">delete</i></button>';
                            echo '</form>';
                        }else{
                            echo '<div class="hozzaszolas">';
                            echo '<strong>' . htmlspecialchars($komment['FELHASZNALONEV']) . '</strong>';
                            echo '<p>' . nl2br(htmlspecialchars($komment['KOMMENT'])) . '</p>';
                            echo '</div>';
                        }
                        
                    }
                    echo '</div>';
                } else {
                    echo '<p>Nincsenek hozzászólások.</p>';
                }
            } else {
                echo '<p>Jelentkezzen be, hogyha szeretne reagálni!</p>';
            }

            // Hozzászólás űrlap
            echo '</div>';
            echo '<hr>';
        }
    } else {
        echo '<p>Nincsenek bejegyzések.</p>';
    }
    ?>
</div>

    <div class="jelolesek">
    <div class="container">
        <?php
        if(isset($_SESSION["felhasznalo"])){
            $jelolesek = felhasznalo_jeloleskerelemek($felhasznalonev);
            if (!empty($jelolesek)) {
                foreach ($jelolesek as $felhasznalo) {
                    echo '<div class="adatok">';
                    echo '<img src="kepek/stock.jpg" alt="Profilkép">';
                    echo '<div class="felhasznalo-info">';
                    echo htmlspecialchars($felhasznalo['NEV']) . ' ('. htmlspecialchars($felhasznalo['FELHASZNALONEV']) . ')';
                    echo '</div>';
                    echo '<div id="button-block">';
                    echo '<button class="gomb" onclick="jelolesKuldese(\''.htmlspecialchars($felhasznalo['FELHASZNALONEV']).'\')">Jelölés</button>';
                    echo '<button class="gomb" onclick="felhasznaloEltavolitasa(\''.htmlspecialchars($felhasznalo['FELHASZNALONEV']).'\')">Eltávolítás</button>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>Nincs több felhasználó akit jelölhetnél.</p>';
            }
        }
        else
        {
            // Nem bejelentkezett felhasználó esetén
            if (!($conn = adatb_betoltes())) {
                die("Adatbázis kapcsolódási hiba");
            }
            
            $sql = "SELECT felhasznalonev, nev FROM felhasznalo";
            $stmt = oci_parse($conn, $sql);
            
            if (!oci_execute($stmt)) {
                $error = oci_error($stmt);
                die("Lekérdezési hiba: " . $error['message']);
            }
            
            while ($felhasznalo = oci_fetch_assoc($stmt)) {
                echo '<div class="adatok">';
                echo '<img src="kepek/stock.jpg" alt="Profilkép">';
                echo '<div class="felhasznalo-info">';
                echo htmlspecialchars($felhasznalo['NEV']) . ' (' . htmlspecialchars($felhasznalo['FELHASZNALONEV']) . ')';
                echo '</div>';
                echo '<div id="button-block">';
                echo '<button class="gomb" onclick="location.href=\'login.php\'">Bejelentkezés</button>';
                echo '</div>';
                echo '</div>';
            }
            
            if (oci_num_rows($stmt) == 0) {
                echo '<p>Nincsenek felhasználók az adatbázisban.</p>';
            }
            
            oci_free_statement($stmt);
            oci_close($conn);
        }
        ?>
    </div>
</div>
</body>
</html>