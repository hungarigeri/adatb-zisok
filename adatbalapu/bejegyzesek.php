<?php
// filepath: c:\xampp\htdocs\adatb_alapu\adatbalapu\bejegyzesek.php
session_start();
ini_set('display_errors', 0);
include "function/database_contr.php";
include "function/dateformatter.php";

// Check if the user is logged in
if (!isset($_SESSION["felhasznalo"])) {
    header("Location: login.php");
    exit;
}

$felhasznalonev = $_SESSION["felhasznalo"]["felhasználónév"];

// Handle form submissions for creating, updating, and deleting posts
$leiras = $_POST["leiras"] ?? "";
$szoveg = $_POST["szoveg"] ?? "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["create"])) {
        $leiras = $_POST["leiras"];
        $szoveg = $_POST["szoveg"];
        $hiba = bejegyzes_mentes($leiras, $szoveg, $felhasznalonev);

    if ($hiba) {
        $hiba_szoveg = $hiba;
    } else {
        header("Location: bejegyzesek.php");
        exit;
    }
    } elseif (isset($_POST["update"])) {
        $bejegyzes_id = $_POST["bejegyzes_id"];
        $leiras = $_POST["leiras"];
        $szoveg = $_POST["szoveg"];
        bejegyzes_modositas($bejegyzes_id, $leiras, $szoveg, $felhasznalonev);
        header("Location: bejegyzesek.php");
    } elseif (isset($_POST["delete"])) {
        $bejegyzes_id = $_POST["bejegyzes_id"];
        bejegyzes_torles($bejegyzes_id, $felhasznalonev);
        header("Location: bejegyzesek.php");
    }
}

// Fetch all posts by the logged-in user
$bejegyzesek = bejegyzesek_lekerese($felhasznalonev);

// Functions for managing posts
function bejegyzes_mentes($leiras, $szoveg, $felhasznalonev) {
    $conn = adatb_betoltes();

    // Tárolt eljárás meghívása
    $sql = "BEGIN BEJEGYZES_HOZZAADAS(:leiras, :szoveg, :felhasznalonev, :bejegyzes_id); END;";
    $stmt = oci_parse($conn, $sql);

    // Paraméterek kötése
    oci_bind_by_name($stmt, ":leiras", $leiras);
    oci_bind_by_name($stmt, ":szoveg", $szoveg);
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);
    oci_bind_by_name($stmt, ":bejegyzes_id", $bejegyzes_id, -1, SQLT_INT); // Kimeneti paraméter

    // Eljárás végrehajtása
    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        if (strpos($error['message'], 'ORA-12899') !== false) {
            // Ha a hiba az oszlop hosszának túllépése miatt történt
            return "A bejegyzés szövege túl hosszú.";
        } else {
            // Egyéb adatbázis hiba
            return "Hiba történt a bejegyzés mentésekor: " . $error['message'];
        }
    }

    // Kép feltöltése, ha van
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
        $imageType = $_FILES['image']['type'];

        if (substr($imageType, 0, 5) === "image") {
            $sql = "INSERT INTO KEPEK (KEP_ID, FOTO, BEJEGYZES_ID) 
                    VALUES (kep_id_seq.NEXTVAL, EMPTY_BLOB(), :bejegyzes_id) 
                    RETURNING FOTO INTO :imageData";
            $stmt = oci_parse($conn, $sql);

            $blob = oci_new_descriptor($conn, OCI_D_LOB);
            oci_bind_by_name($stmt, ":imageData", $blob, -1, OCI_B_BLOB);
            oci_bind_by_name($stmt, ":bejegyzes_id", $bejegyzes_id);

            if (oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
                if ($blob->save($imageData)) {
                    oci_commit($conn);
                    echo "Kép sikeresen feltöltve.";
                } else {
                    echo "Hiba a BLOB mentésekor.";
                    oci_rollback($conn);
                }
            } else {
                $error = oci_error($stmt);
                echo "Hiba a kép feltöltésekor: " . $error['message'];
                oci_rollback($conn);
            }

            $blob->free();
            oci_free_statement($stmt);
        } else {
            echo "Nem megfelelő fájltípus.";
        }
    }
    oci_free_statement($stmt);
    oci_close($conn);
    return null;
}

function bejegyzes_modositas($bejegyzes_id, $leiras, $szoveg, $felhasznalonev) {
    $conn = adatb_betoltes();
    $sql = "UPDATE BEJEGYZES 
            SET LEIRAS = :leiras, 
                SZOVEG = :szoveg 
            WHERE BEJEGYZES_ID = :bejegyzes_id 
              AND FELHASZNALONEV = :felhasznalonev";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":leiras", $leiras);
    oci_bind_by_name($stmt, ":szoveg", $szoveg);
    oci_bind_by_name($stmt, ":bejegyzes_id", $bejegyzes_id);
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        echo "Hiba történt a bejegyzés módosításakor: " . $error['message'];
    }

    oci_free_statement($stmt);
    oci_close($conn);
}
function bejegyzes_torles($bejegyzes_id, $felhasznalonev) {
    $conn = adatb_betoltes();
   
    $sql = "DELETE FROM BEJEGYZES 
            WHERE BEJEGYZES_ID = :bejegyzes_id AND FELHASZNALONEV = :felhasznalonev";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":bejegyzes_id", $bejegyzes_id);
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);
    oci_execute($stmt);
    oci_free_statement($stmt);

   
    $sql = "DELETE FROM KEPEK WHERE BEJEGYZES_ID = :bejegyzes_id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":bejegyzes_id", $bejegyzes_id);
    oci_execute($stmt);
    oci_free_statement($stmt);

    oci_close($conn); 
}

function bejegyzesek_lekerese($felhasznalonev) {
    $conn = adatb_betoltes();
    $sql = "SELECT b.BEJEGYZES_ID, b.LEIRAS, b.SZOVEG, TO_CHAR(b.FELTOLTES_IDEJE, 'YYYY-MM-DD HH24:MI:SS') AS FELTOLTES_IDEJE, 
                   k.FOTO, 
                   (SELECT COUNT(*) FROM LAJKOLAS l WHERE l.BEJEGYZES_ID = b.BEJEGYZES_ID) AS LAJKOK_SZAMA
            FROM BEJEGYZES b
            LEFT JOIN KEPEK k ON b.BEJEGYZES_ID = k.BEJEGYZES_ID
            WHERE b.FELHASZNALONEV = :felhasznalonev 
            ORDER BY b.FELTOLTES_IDEJE DESC";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);
    oci_execute($stmt);
    $bejegyzesek = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $bejegyzesek[] = $row;
    }
    oci_free_statement($stmt);
    oci_close($conn);
    return $bejegyzesek;
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
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Bejegyzések</title>
    <link rel="stylesheet" href="style/bejegyzesek.css">
    <link rel="stylesheet" href="style/alap.css">
    <link rel="icon" type="image/png" href="kepek/logo_no_background.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
<nav>
    <div class="fiok">
        <a href="index.php">Főoldal</a>
        <a href="szulinapok.php">Születésnapok</a>
        <a href="profile.php">Fiók</a>
        <a href="csoportok.php">Csoportok</a>
        <a href="function/logout.php">Kijelentkezés</a>
    </div>
</nav>

<h2>Bejegyzéseim</h2>

<form class="bejegyzes_felvitel" action="bejegyzesek.php" method="post" enctype="multipart/form-data">
    <h3>Új bejegyzés</h3>
    <label>
        Leírás:
        <input type="text" name="leiras" value="<?php echo htmlspecialchars($leiras); ?>" required>
    </label>
    <label>
        Szöveg:
        <textarea name="szoveg" rows="5" required><?php echo htmlspecialchars($szoveg); ?></textarea>
    </label>
    <label>
        Kép feltöltése:
        <input type="file" name="image">
    </label>
    <button type="submit" name="create">Létrehozás</button>
    <?php if (!empty($hiba_szoveg)): ?>
        <p class="hiba"><?php echo htmlspecialchars($hiba_szoveg); ?></p>
    <?php endif; ?>
</form>


<hr>

<?php foreach ($bejegyzesek as $bejegyzes): ?>
    <div class="bejegyzes">
        <div class="details-container">
            <!-- Kép megjelenítése, ha van -->
            <?php if (!empty($bejegyzes["FOTO"])): ?>
                <img src="function/kep_megjelenites.php?bejegyzes_id=<?php echo $bejegyzes['BEJEGYZES_ID']; ?>" alt="Bejegyzés képe">
            <?php endif; ?>

            <form action="bejegyzesek.php" method="post" style="display: inline;">
                <small class="post-time">Utoljára módosítva: <?php echo $bejegyzes["FELTOLTES_IDEJE"]; ?></small>
                <input type="hidden" name="bejegyzes_id" value="<?php echo $bejegyzes["BEJEGYZES_ID"]; ?>">
                <label>
                    <input type="text" name="leiras" value="<?php echo htmlspecialchars($bejegyzes["LEIRAS"]); ?>" required>
                    
                </label>
                <label>
                    <textarea name="szoveg" rows="3" required><?php echo htmlspecialchars($bejegyzes["SZOVEG"]); ?></textarea>
                </label>
                <div class="like-container">
                    <i class="material-icons">favorite</i>
                    <span><?php echo $bejegyzes["LAJKOK_SZAMA"]; ?> </span>
                </div>
        </div>
            <div class="button-container">
                <button type="submit" name="update">
                    <i class="material-icons">edit</i> 
                </button>
                <button type="submit" name="delete">
                    <i class="material-icons">delete</i>
                </button>
            </div>
        </form>

        <!-- Kommentek megjelenítése -->
        <div class="comments">
            <h4>Kommentek:</h4>
            <?php 
            $kommentek = kommentek_lekerese($bejegyzes["BEJEGYZES_ID"]); // Kommentek lekérése
            if (!empty($kommentek)): ?>
                <?php foreach ($kommentek as $komment): ?>
                    <div class="comment">
                        <strong><?php echo htmlspecialchars($komment["FELHASZNALONEV"]); ?>:</strong>
                        <p><?php echo htmlspecialchars($komment["KOMMENT"]); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nincsenek kommentek.</p>
            <?php endif; ?>
        </div>

        <!-- Új komment hozzáadása -->
        <form action="bejegyzesek.php" method="post">
            <input type="hidden" name="bejegyzes_id" value="<?php echo $bejegyzes["BEJEGYZES_ID"]; ?>">
            <textarea name="komment_szoveg" rows="2" placeholder="Írj egy kommentet..." required></textarea>
            <button type="submit" name="add_comment">
                <i class="material-icons">send</i> 
            </button>
        </form>
    </div>
    <hr>
<?php endforeach; ?>

</body>
</html>