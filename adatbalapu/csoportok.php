<?php
// Use absolute paths for better reliability
include __DIR__ . "/function/dateformatter.php";
include __DIR__ . "/function/statisztikak.php";
session_start();

if(!isset($_SESSION["felhasznalo"])){
    header("Location: index.php");
}
$conn = adatb_betoltes();

$felhasznalonev = $_SESSION["felhasznalo"]["felhasználónév"];

$csoport_tagok=csoport_letszam();
$csoport_tagok_osszismeros=csoporttagok_osszismeros();

$letszamok = [];
foreach ($csoport_tagok as $csoport) {
    $letszamok[$csoport['CSOPORTNEV']] = $csoport['LETSZAM'];
}
$osszismeros = [];
foreach ($csoport_tagok_osszismeros as $csoport) {
    $osszismeros[$csoport['CSOPORTNEV']] = $csoport['ISMEROSOK'];
}

// Group creation handling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uj_csoport'])) {
    $csoport_nev = trim($_POST['nev']);
    $csoport_leiras = trim($_POST['leiras']);

    if ($csoport_nev !== '') {
        try {
            // Start transaction
            oci_execute(oci_parse($conn, "BEGIN"));
            
            // Get next group ID
            $sql_id = "SELECT NVL(MAX(CSOPORT_ID), 0) + 1 AS UJ_ID FROM CSOPORT";
            $stmt_id = oci_parse($conn, $sql_id);
            oci_execute($stmt_id);
            oci_fetch($stmt_id);
            $uj_id = oci_result($stmt_id, "UJ_ID");
            oci_free_statement($stmt_id);

            // 1. Create the group
            $sql = "INSERT INTO CSOPORT (CSOPORT_ID, NEV, LEIRAS) VALUES (:id, :nev, :leiras)";
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ":id", $uj_id);
            oci_bind_by_name($stmt, ":nev", $csoport_nev);
            oci_bind_by_name($stmt, ":leiras", $csoport_leiras);
            oci_execute($stmt);

            // 2. Add user as group manager
            $sql_kezeles = "INSERT INTO CSOPORTOT_KEZEL (FELHASZNALONEV, CSOPORT_ID) VALUES (:felhasznalonev, :id)";
            $stmt_kezeles = oci_parse($conn, $sql_kezeles);
            oci_bind_by_name($stmt_kezeles, ":felhasznalonev", $felhasznalonev);
            oci_bind_by_name($stmt_kezeles, ":id", $uj_id);
            oci_execute($stmt_kezeles);

            // 3. Add user as group member
            $sql_tag = "INSERT INTO CSOPORT_TAGJA (CSOPORT_ID, FELHASZNALONEV) VALUES (:csoport_id, :felhasznalonev)";
            $stmt_tag = oci_parse($conn, $sql_tag);
            oci_bind_by_name($stmt_tag, ":csoport_id", $uj_id);
            oci_bind_by_name($stmt_tag, ":felhasznalonev", $felhasznalonev);
            oci_execute($stmt_tag);

            // Commit transaction
            oci_execute(oci_parse($conn, "COMMIT"));
            
            header("Location: csoportok.php?created=1");
            exit;
        } catch (Exception $e) {
            // Rollback on error
            oci_execute(oci_parse($conn, "ROLLBACK"));
            $error_message = "Hiba történt: " . htmlspecialchars($e->getMessage());
        }
    } else {
        $error_message = "A csoport neve kötelező!";
    }
}

// Handle group modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modosit'])) {
    $csoport_id = $_POST['csoport_id'];
    $uj_nev = trim($_POST['nev']);
    $uj_leiras = trim($_POST['leiras']);
    $uj_tulajdonos = isset($_POST['uj_tulajdonos']) ? trim($_POST['uj_tulajdonos']) : null;

    try {
        // Start transaction
        oci_execute(oci_parse($conn, "BEGIN"));
        
        // 1. Update group info
        $sql = "UPDATE CSOPORT SET NEV = :nev, LEIRAS = :leiras WHERE CSOPORT_ID = :id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":nev", $uj_nev);
        oci_bind_by_name($stmt, ":leiras", $uj_leiras);
        oci_bind_by_name($stmt, ":id", $csoport_id);
        oci_execute($stmt);

        // 2. If new owner is specified, transfer ownership
        if ($uj_tulajdonos && $uj_tulajdonos !== '') {
            // First delete current manager
            $sql_delete = "DELETE FROM CSOPORTOT_KEZEL WHERE CSOPORT_ID = :id AND FELHASZNALONEV = :current_user";
            $stmt_delete = oci_parse($conn, $sql_delete);
            oci_bind_by_name($stmt_delete, ":id", $csoport_id);
            oci_bind_by_name($stmt_delete, ":current_user", $felhasznalonev);
            oci_execute($stmt_delete);

            // Then add new manager
            $sql_insert = "INSERT INTO CSOPORTOT_KEZEL (FELHASZNALONEV, CSOPORT_ID) VALUES (:new_user, :id)";
            $stmt_insert = oci_parse($conn, $sql_insert);
            oci_bind_by_name($stmt_insert, ":new_user", $uj_tulajdonos);
            oci_bind_by_name($stmt_insert, ":id", $csoport_id);
            oci_execute($stmt_insert);
        }

        // Commit transaction
        oci_execute(oci_parse($conn, "COMMIT"));
        
        header("Location: csoportok.php?updated=1");
        exit;
    } catch (Exception $e) {
        // Rollback on error
        oci_execute(oci_parse($conn, "ROLLBACK"));
        $error_message = "Hiba történt módosítás közben: " . htmlspecialchars($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Csoportok</title>
    <link rel="stylesheet" href="style/alap.css">
    <link rel="stylesheet" href="style/csoportok.css">
    <link rel="icon" type="image/png" href="kepek/logo_no_background.png">
    <script>
        function csatlakozasCsoporthoz(csoportId) {
    if (confirm('Biztosan csatlakozni szeretnél ehhez a csoporthoz?')) {
        fetch('function/csoport_csatlakozasa.php?id=' + encodeURIComponent(csoportId), {
            method: 'GET'
        })
        .then(response => {
            if (response.ok) {
                return response.text();
            }
            throw new Error('Network response was not ok');
        })
        .then(() => {
            // Success - redirect to group details page
            window.location.href = 'csoport_reszletek.php?id=' + csoportId;
        })
        .catch(error => {
            console.error('Hiba:', error);
            alert('Hiba történt a csatlakozás közben');
        });
    }
}
        function toggleEditForm(csoportId) {
            var form = document.getElementById('edit-form-' + csoportId);
            if (form.style.display === 'none') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
        
        function confirmDelete(csoportId) {
            return confirm('Biztosan törölni szeretnéd ezt a csoportot?');
        }
    </script>
</head>
<body>
    <nav>
        <a href="index.php">Főoldal</a>
        <div class="fiok">
            <a href="profile.php">Profil</a>
            <a href="function/logout.php">Kijelentkezés</a>
        </div>
    </nav>

    <?php if (!empty($error_message)): ?>
        <div class="hiba"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <h1>Csoport létrehozása</h1>
    <div class="uj-csoport-form">
        <form method="post" action="">
            <label for="nev">Csoport neve:</label>
            <input type="text" id="nev" name="nev" required class="textarea">
            <label for="leiras">Leírás:</label>
            <textarea class="textarea" id="leiras" name="leiras" rows="4"></textarea>
            <br><br>
            <button type="submit" name="uj_csoport" class="csoport-gomb">Csoport létrehozása</button>
        </form>
    </div>




    <h1>Csoportok</h1>
    <div class="csoport-container">
        <?php
        $csoportok = csoportok_lekerdezese($felhasznalonev);

        $osszevont = [];
        foreach ($csoportok as $csoport) {
            $nev = $csoport['NEV'];
            if (isset($letszamok[$nev])) {
                $csoport['LETSZAM'] = $letszamok[$nev];
                $csoport['ISMEROSOK'] = $osszismeros[$nev];
            } else {
                $csoport['LETSZAM'] = 0;
                $csoport['ISMEROSOK'] = 0;
            }
            $osszevont[] = $csoport;
        }

        
        if ($osszevont && count($osszevont) > 0) {
            foreach ($osszevont as $csoport) {
                echo '<div class="csoport-kartya">';
                echo '<div class="csoport-fejlec">';
                echo '<h2>' . htmlspecialchars($csoport['NEV']) . '</h2>';
                echo '</div>';
                echo '<div class="csoport-tartalom">';
                echo '<p>' . htmlspecialchars($csoport['LEIRAS']) . '</p>';
                echo '<p class="csoport-datum">Létrehozva: ' . formatdate($csoport['LETREHOZAS_DATUMA']) . '</p>';
                echo '<p class="csoport-datum">Tagok száma: ' . htmlspecialchars($csoport['LETSZAM']) . '</p>';
                echo '<p class="csoport-datum">Tagok összesített ismerősszáma: ' . htmlspecialchars($csoport['ISMEROSOK']) . '</p>';
                echo '</div>';
                echo '<div class="csoport-lablec">';
                echo '<button onclick="csatlakozasCsoporthoz(' . $csoport['CSOPORT_ID'] . ')" class="csoport-gomb">Csatlakozás</button>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p class="nincs-csoport">Nincsenek elérhető csoportok.</p>';
        }
        ?>
    </div>

    <h1>Saját Csoportok</h1>
    <div class="csoport-container">
        <?php 
        $csoportok = sajat_csoportok_lekerdezese($felhasznalonev);

        $osszevont = [];
        foreach ($csoportok as $csoport) {
            $nev = $csoport['NEV'];
            if (isset($letszamok[$nev])) {
                $csoport['LETSZAM'] = $letszamok[$nev];
                $csoport['ISMEROSOK'] = $osszismeros[$nev];
            } else {
                $csoport['LETSZAM'] = 0;
                $csoport['ISMEROSOK'] = 0;
            }
            $osszevont[] = $csoport;
        }

        if ($osszevont && count($osszevont) > 0) {
            foreach ($osszevont as $csoport) {
                echo '<div class="csoport-kartya">';
                echo '<div class="csoport-fejlec">';
                echo '<h2>' . htmlspecialchars($csoport['NEV']) . '</h2>';
                echo '</div>';
                echo '<div class="csoport-tartalom">';
                echo '<p>' . htmlspecialchars($csoport['LEIRAS']) . '</p>';
                echo '<p class="csoport-datum">Létrehozva: ' . formatdate($csoport['LETREHOZAS_DATUMA']) . '</p>';
                echo '<p class="csoport-datum">Tagok száma: ' . htmlspecialchars($csoport['LETSZAM']) . '</p>';
                echo '<p class="csoport-datum">Tagok összesített ismerősszáma: ' . htmlspecialchars($csoport['ISMEROSOK']) . '</p>';
                echo '</div>';
                echo '<div class="csoport-lablec">';
                echo '<a href="csoport_reszletek.php?id=' . $csoport['CSOPORT_ID'] . '" class="csoport-gomb">Részletek</a>';
                echo '<button onclick="toggleEditForm(' . $csoport['CSOPORT_ID'] . ')" class="csoport-gomb">Módosítás</button>';
                echo '</div>';
                
                // Edit form (hidden by default)
                echo '<div id="edit-form-' . $csoport['CSOPORT_ID'] . '" style="display:none; margin-top:10px; padding:10px; border:1px solid #ccc;">';
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="csoport_id" value="' . $csoport['CSOPORT_ID'] . '">';
                echo '<label for="nev">Csoport neve:</label>';
                echo '<input type="text" name="nev" value="' . htmlspecialchars($csoport['NEV']) . '" required class="textarea">';
                echo '<label for="leiras">Leírás:</label>';
                echo '<textarea class="textarea" name="leiras" rows="4">' . htmlspecialchars($csoport['LEIRAS']) . '</textarea>';
                
                // Check if there are other members to transfer ownership
                $sql_tagok = "SELECT COUNT(*) AS TAGOK_SZAMA FROM CSOPORT_TAGJA WHERE CSOPORT_ID = :csoport_id";
                $stmt_tagok = oci_parse($conn, $sql_tagok);
                oci_bind_by_name($stmt_tagok, ":csoport_id", $csoport['CSOPORT_ID']);
                oci_execute($stmt_tagok);
                oci_fetch($stmt_tagok);
                $tagok_szama = oci_result($stmt_tagok, "TAGOK_SZAMA");
                oci_free_statement($stmt_tagok);
                
                if ($tagok_szama > 1) {
                    echo '<label for="uj_tulajdonos">Csoport átadása (opcionális):</label>';
                    echo '<select name="uj_tulajdonos" class="textarea">';
                    echo '<option value="">-- Válassz új tulajdonost --</option>';
                    
                    $sql_tagok_lista = "SELECT FELHASZNALONEV FROM CSOPORT_TAGJA 
                                       WHERE CSOPORT_ID = :csoport_id 
                                       AND FELHASZNALONEV != :current_user";
                    $stmt_tagok_lista = oci_parse($conn, $sql_tagok_lista);
                    oci_bind_by_name($stmt_tagok_lista, ":csoport_id", $csoport['CSOPORT_ID']);
                    oci_bind_by_name($stmt_tagok_lista, ":current_user", $felhasznalonev);
                    oci_execute($stmt_tagok_lista);
                    
                    while ($tag = oci_fetch_array($stmt_tagok_lista, OCI_ASSOC)) {
                        echo '<option value="' . htmlspecialchars($tag['FELHASZNALONEV']) . '">' . 
                             htmlspecialchars($tag['FELHASZNALONEV']) . '</option>';
                    }
                    oci_free_statement($stmt_tagok_lista);
                    
                    echo '</select>';
                }
                
                echo '<button type="submit" name="modosit" class="csoport-gomb">Mentés</button>';
                echo '</form>';
                echo '</div>';
                
                echo '</div>';
            }
        } else {
            echo '<p class="nincs-csoport">Nincsenek elérhető csoportok.</p>';
        }
        ?>
    </div>

    <h1>Létrehozott Csoportok</h1>
    <div class="csoport-container">
        <?php 
        $csoportok = sajat_csoportok_törlése($felhasznalonev);

        $osszevont = [];
        foreach ($csoportok as $csoport) {
            $nev = $csoport['NEV'];
            if (isset($letszamok[$nev])) {
                $csoport['LETSZAM'] = $letszamok[$nev];
                $csoport['ISMEROSOK'] = $osszismeros[$nev];
            } else {
                $csoport['LETSZAM'] = 0;
                $csoport['ISMEROSOK'] = 0;
            }
            $osszevont[] = $csoport;
        }

        if ($osszevont && count($osszevont) > 0) {
            foreach ($osszevont as $csoport) {
                echo '<div class="csoport-kartya">';
                echo '<div class="csoport-fejlec">';
                echo '<h2>' . htmlspecialchars($csoport['NEV']) . '</h2>';
                echo '</div>';
                echo '<div class="csoport-tartalom">';
                echo '<p>' . htmlspecialchars($csoport['LEIRAS']) . '</p>';
                echo '<p class="csoport-datum">Létrehozva: ' . formatdate($csoport['LETREHOZAS_DATUMA']) . '</p>';
                echo '<p class="csoport-datum">Tagok száma: ' . htmlspecialchars($csoport['LETSZAM']) . '</p>';
                echo '<p class="csoport-datum">Tagok összesített ismerősszáma: ' . htmlspecialchars($csoport['ISMEROSOK']) . '</p>';
                echo '</div>';
                echo '<div class="csoport-lablec">';
                echo '<a href="csoport_torles.php?id=' . $csoport['CSOPORT_ID'] . '" class="csoport-gomb" onclick="return confirmDelete(' . $csoport['CSOPORT_ID'] . ')">Törlés</a>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p class="nincs-csoport">Nincsenek létrehozott csoportok.</p>';
        }
        ?>
    </div>
</body>
</html>