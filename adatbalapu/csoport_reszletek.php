<?php
include "function/dateformatter.php";
include "function/statisztikak.php";
session_start();

if (!isset($_SESSION["felhasznalo"])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: csoportok.php");
    exit;
}

$csoport_id = $_GET['id'];
$felhasznalonev = $_SESSION["felhasznalo"]["felhasználónév"];

$legidosebb=csoport_legidosebb($csoport_id);
if($legidosebb!=null){
    $legidosebb_felhasznalonev=$legidosebb["FELHASZNALONEV"];
    $szulinap=intval(substr((formatdate($legidosebb["SZULINAP"])),0,4));
    $legidesebb_kor=intval(date('Y'))-$szulinap;
}


// Check if user is member of this group
$is_member = false;
$conn = adatb_betoltes();
$sql = "SELECT COUNT(*) AS DB FROM CSOPORT_TAGJA 
        WHERE CSOPORT_ID = :csoport_id AND FELHASZNALONEV = :felhasznalonev";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":csoport_id", $csoport_id);
oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);
oci_execute($stmt);
oci_fetch($stmt);
$is_member = (oci_result($stmt, "DB") > 0);
oci_free_statement($stmt);

if (!$is_member) {
    header("Location: csoportok.php?error=not_member");
    exit;
}

// Get group details
$sql = "SELECT * FROM CSOPORT WHERE CSOPORT_ID = :csoport_id";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":csoport_id", $csoport_id);
oci_execute($stmt);
$csoport = oci_fetch_assoc($stmt);
oci_free_statement($stmt);

// Get group posts
$sql = "SELECT b.*, k.FOTO 
        FROM CSOPORT_BEJEGYZES cb
        JOIN BEJEGYZES b ON cb.BEJEGYZES_ID = b.BEJEGYZES_ID
        LEFT JOIN KEPEK k ON b.BEJEGYZES_ID = k.BEJEGYZES_ID
        WHERE cb.CSOPORT_ID = :csoport_id
        ORDER BY b.FELTOLTES_IDEJE DESC";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":csoport_id", $csoport_id);
oci_execute($stmt);
$bejegyzesek = array();
while ($row = oci_fetch_assoc($stmt)) {
    $bejegyzesek[] = $row;
}
oci_free_statement($stmt);
oci_close($conn);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($csoport['NEV']); ?> - Csoport részletek</title>
    <link rel="stylesheet" href="style/alap.css">
    <link rel="stylesheet" href="style/csoport_reszletek.css">
    <link rel="icon" type="image/png" href="kepek/logo_no_background.png">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
function bejegyzesTorlese(bejegyzesId) {
    if (confirm('Biztosan törölni szeretnéd ezt a bejegyzést?')) {
        console.log('Törlés kérés elküldve:', bejegyzesId); // Debug üzenet
        
        $.ajax({
            url: 'function/bejegyzes_torles.php',
            type: 'POST',
            data: {
                bejegyzes_id: bejegyzesId,
                csoport_id: <?php echo $csoport_id; ?>
            },
            success: function(response) {
                console.log('Válasz:', response); // Debug üzenet
                if(response === 'success') {
                    // Eltávolítjuk a törölt bejegyzést a DOM-ból
                    $('#bejegyzes-' + bejegyzesId).remove();
                    
                    // Ha nincs több bejegyzés, megjelenítjük az üzenetet
                    if ($('.bejegyzes').length === 0) {
                        $('.bejegyzesek').append('<p>Még nincsenek bejegyzések ebben a csoportban.</p>');
                    }
                } else {
                    alert('Hiba: ' + response);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX hiba:', status, error); // Debug üzenet
                alert('Hiba történt a bejegyzés törlése közben!');
            }
        });
    }
}
// Bejegyzés módosítása
function bejegyzesModositasa(bejegyzesId) {
    const bejegyzesElem = document.getElementById(`bejegyzes-${bejegyzesId}`);
    const szovegElem = bejegyzesElem.querySelector('.bejegyzes-tartalom p');
    
    // Elmentjük az eredeti szöveget
    const eredeti = szovegElem.textContent;
    
    // Szövegmező létrehozása
    const input = document.createElement('textarea');
    input.value = eredeti;
    input.className = 'modosit-input';
    input.style.width = '100%';
    input.style.minHeight = '100px';
    
    // Gombok létrehozása
    const mentesGomb = document.createElement('button');
    mentesGomb.textContent = 'Mentés';
    mentesGomb.className = 'mentes-gomb';
    mentesGomb.onclick = function() {
        bejegyzesMentes(bejegyzesId, input.value);
    };
    
    const megsemGomb = document.createElement('button');
    megsemGomb.textContent = 'Mégsem';
    megsemGomb.className = 'megsem-gomb';
    megsemGomb.onclick = function() {
        visszaallitBejegyzes(bejegyzesId, eredeti);
    };
    
    // Cseréljük ki a szöveget a beviteli mezőre
    szovegElem.replaceWith(input);
    
    // Eltüntetjük a módosítás és törlés gombokat
    bejegyzesElem.querySelector('.torles-gomb').style.display = 'none';
    bejegyzesElem.querySelector('.modosit-gomb').style.display = 'none';
    
    // Hozzáadjuk a gombokat
    const gombokDiv = document.createElement('div');
    gombokDiv.className = 'modosit-gombok';
    gombokDiv.appendChild(mentesGomb);
    gombokDiv.appendChild(megsemGomb);
    
    bejegyzesElem.querySelector('.bejegyzes-tartalom').appendChild(gombokDiv);
    input.focus();
}

// Visszaállítás eredeti állapotra
function visszaallitBejegyzes(bejegyzesId, eredetiSzoveg) {
    const bejegyzesElem = document.getElementById(`bejegyzes-${bejegyzesId}`);
    const input = bejegyzesElem.querySelector('.modosit-input');
    
    // Új szövegelem létrehozása
    const szovegElem = document.createElement('p');
    szovegElem.textContent = eredetiSzoveg;
    
    // Visszacseréljük
    input.replaceWith(szovegElem);
    
    // Eltávolítjuk a gombokat
    bejegyzesElem.querySelector('.modosit-gombok').remove();
    
    // Visszaállítjuk a módosítás és törlés gombokat
    bejegyzesElem.querySelector('.torles-gomb').style.display = 'inline-block';
    bejegyzesElem.querySelector('.modosit-gomb').style.display = 'inline-block';
}

// Bejegyzés mentése
function bejegyzesMentes(bejegyzesId, ujSzoveg) {
    if (ujSzoveg.trim() === '') {
        alert('A bejegyzés nem lehet üres!');
        return;
    }
    
    $.ajax({
        url: 'function/bejegyzes_modosit.php',
        type: 'POST',
        data: {
            bejegyzes_id: bejegyzesId,
            uj_szoveg: ujSzoveg
        },
        success: function(response) {
            const bejegyzesElem = document.getElementById(`bejegyzes-${bejegyzesId}`);
            const input = bejegyzesElem.querySelector('.modosit-input');
            
            // Új szövegelem létrehozása
            const szovegElem = document.createElement('p');
            szovegElem.textContent = ujSzoveg;
            
            // Visszacseréljük
            input.replaceWith(szovegElem);
            
            // Eltávolítjuk a gombokat
            bejegyzesElem.querySelector('.modosit-gombok').remove();
            
            // Visszaállítjuk a módosítás és törlés gombokat
            bejegyzesElem.querySelector('.torles-gomb').style.display = 'inline-block';
            bejegyzesElem.querySelector('.modosit-gomb').style.display = 'inline-block';
            
            // Dátum frissítése
            bejegyzesElem.querySelector('.datum').textContent += ' (módosítva)';
        },
        error: function(xhr, status, error) {
            console.error('AJAX hiba:', status, error);
            alert('Hiba történt a bejegyzés módosítása közben!');
        }
    });
}
function jelentesModal(bejegyzesId, felhasznalo) {
    const modal = document.getElementById('jelentesModal');
    document.getElementById('jelentesBejegyzesId').value = bejegyzesId;
    document.getElementById('jelentesFelhasznalo').value = felhasznalo;
    modal.style.display = 'block';
}

// Modal bezárása
document.addEventListener('DOMContentLoaded', function() {
    const closeButton = document.querySelector('.close');
    if (closeButton) {
        closeButton.onclick = function() {
            const modal = document.getElementById('jelentesModal');
            if (modal) {
                modal.style.display = 'none';
            }
        };
    }
});

// Modal bezárása kívülre kattintva
window.onclick = function(event) {
    const modal = document.getElementById('jelentesModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// Jelentés form elküldése
// Jelentés form elküldése
$(document).ready(function() {
    $('#jelentesForm').on('submit', function(e) {
        e.preventDefault();
        console.log("Form submit esemény aktiválva");
        
        // Collect form data
        const formData = {
            bejegyzes_id: $('#jelentesBejegyzesId').val(),
            jelentett_felhasznalo: $('#jelentesFelhasznalo').val(),
            tipus: $('#jelentesTipus').val(),
            leiras: $('#jelentesLeiras').val()
        };

        console.log("Küldött adatok:", formData);

        // Disable button during submission
        const submitBtn = $(this).find('.jelentes-submit');
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Küldés...');

        // AJAX call
        $.ajax({
            url: '/function/bejegyzes_jelentes.php',
            type: 'POST',
            dataType: 'json',
            data: formData,
            success: function(response) {
                console.log("Válasz:", response);
                if(response.success) {
                    alert('Jelentés sikeresen elküldve!');
                    $('#jelentesModal').hide();
                } else {
                    alert('Hiba: ' + (response.error || 'Ismeretlen hiba'));
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX hiba:', status, error);
                alert('Hiba történt a jelentés elküldése közben!');
            },
            complete: function() {
                submitBtn.prop('disabled', false).text('Jelentés elküldése');
            }
        });
    });
});
</script>

</head>
<body>
    <nav>
        <a href="index.php">Főoldal</a>
        <a href="csoportok.php">Vissza a csoportokhoz</a>
        <div class="fiok">
            <a href="profile.php">Profil</a>
            <a href="function/logout.php">Kijelentkezés</a>
        </div>
    </nav>

    <div class="csoport-fejlec">
        <h1><?php echo htmlspecialchars($csoport['NEV']); ?></h1>
        <p><?php echo htmlspecialchars($csoport['LEIRAS']); ?></p>
        <p class="csoport-datum">Létrehozva: <?php echo formatdate($csoport['LETREHOZAS_DATUMA']); ?></p>
    </div>

    <div class="uj-bejegyzes">
        <h2>Új bejegyzés</h2>
        <form method="post" action="function/csoport_bejegyzes_hozzaadasa.php" enctype="multipart/form-data">
            <input type="hidden" name="csoport_id" value="<?php echo $csoport_id; ?>">
            <textarea name="szoveg" placeholder="Írd ide a bejegyzésed..." required></textarea>
            <input type="file" name="kep" accept="image/*">
            <button type="submit" name="submit">Közzététel</button>
        </form>
    </div>

    <div class="bejegyzesek">
    <h2>Bejegyzések</h2>
    <?php if (count($bejegyzesek) > 0): ?>
        <?php foreach ($bejegyzesek as $bejegyzes): ?>
            <div class="bejegyzes" id="bejegyzes-<?php echo $bejegyzes['BEJEGYZES_ID']; ?>">
    <div class="bejegyzes-fejlec">
        <span class="felhasznalo"><?php echo htmlspecialchars($bejegyzes['FELHASZNALONEV']); ?></span>
        <span class="datum"><?php echo formatdate($bejegyzes['FELTOLTES_IDEJE']); ?></span>
        <?php if ($bejegyzes['FELHASZNALONEV'] === $_SESSION["felhasznalo"]["felhasználónév"]): ?>
            <button class="torles-gomb" onclick="bejegyzesTorlese(<?php echo $bejegyzes['BEJEGYZES_ID']; ?>)">Törlés</button>
            <button class="modosit-gomb" onclick="bejegyzesModositasa(<?php echo $bejegyzes['BEJEGYZES_ID']; ?>)">Módosítás</button>
        <?php else: ?>
            <button class="jelentes-gomb" onclick="jelentesModal(<?php echo $bejegyzes['BEJEGYZES_ID']; ?>, '<?php echo htmlspecialchars($bejegyzes['FELHASZNALONEV']); ?>')">Jelentés</button>
        <?php endif; ?>
    </div>


    <div class="bejegyzes-tartalom">
        <p><?php echo htmlspecialchars($bejegyzes['SZOVEG']); ?></p>
        <?php if (!empty($bejegyzes['FOTO'])): ?>
            <img src="function/kep_megjelenites.php?bejegyzes_id=<?php echo $bejegyzes['BEJEGYZES_ID']; ?>" alt="Bejegyzés képe">
        <?php endif; ?>
    </div>
</div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Még nincsenek bejegyzések ebben a csoportban.</p>
    <?php endif; ?>
</div>

    <div>
        <h2>A csoport bölcse</h2>
        <p><?php if($legidosebb==null){
                echo "A csoportban nincs olyan tag, aki megadta volna a születésnapját.";}
            else{
                echo "A csoport legidősebb tagja " . $legidosebb_felhasznalonev . " aki " . $legidesebb_kor . " éves.";}?></p>
    </div>
    <!-- Jelentés modal ablak -->
    <div id="jelentesModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Bejegyzés jelentése</h2>
        <form id="jelentesForm">
            <input type="hidden" id="jelentesBejegyzesId" name="bejegyzes_id">
            <input type="hidden" id="jelentesFelhasznalo" name="jelentett_felhasznalo">
            
            <div class="form-group">
                <label for="jelentesTipus">Jelentés típusa:</label>
                <select id="jelentesTipus" name="tipus" required>
                    <option value="">Válassz típust...</option>
                    <option value="Tiltott tartalom">Tiltott tartalom</option>
                    <option value="Zaklatás">Zaklatás</option>
                    <option value="Gyűlöletkeltés">Gyűlöletkeltés</option>
                    <option value="Spam">Spam</option>
                    <option value="Egyéb">Egyéb</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="jelentesLeiras">Leírás:</label>
                <textarea id="jelentesLeiras" name="leiras" required></textarea>
            </div>
            
            <button type="submit" class="jelentes-submit">Jelentés elküldése</button>
        </form>
    </div>
</div>
</body>
</html>