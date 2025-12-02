<?php
include "function/database_contr.php";
session_start();
if(isset($_SESSION["felhasznalo"])){
    $felhasznalonev = $_SESSION["felhasznalo"]["felhasználónév"];
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Beszélgetések</title>
    <link rel="stylesheet" href="style/alap.css">
    <link rel="stylesheet" href="style/cseveges.css">
    <link rel="icon" type="image/png" href="kepek/logo_no_background.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
function betoltBeszelgetes(partnerFelhasznalonev) {
    // Beállítjuk a partner adatait a fejlécben
    $('.beszelgetes-fejlec').data('partner', partnerFelhasznalonev);
    $('.beszelgetes-fejlec h3').text('Beszélgetés: ' + partnerFelhasznalonev);
    
    // AJAX kérés a beszélgetés betöltéséhez
    $.ajax({
        url: 'function/beszelgetes_betolt.php',
        type: 'POST',
        data: {
            partner: partnerFelhasznalonev
        },
        success: function(response) {
            $('#uzenetek').html(response);
            // Görgessünk az aljára
            $('#uzenetek').scrollTop($('#uzenetek')[0].scrollHeight);
        },
        error: function() {
            alert('Hiba történt a beszélgetés betöltése közben!');
        }
    });
}

function uzenetKuldes() {
    const uzenetSzoveg = $('#uzenetSzoveg').val();
    const partner = $('.beszelgetes-fejlec').data('partner');
    
    if (uzenetSzoveg.trim() === '') {
        alert('Az üzenet nem lehet üres!');
        return;
    }
    
    if (!partner) {
        alert('Először válassz ki egy partnert a beszélgetéshez!');
        return;
    }
    
    $.ajax({
        url: 'function/uzenet_kuld.php',
        type: 'POST',
        data: {
            partner: partner,
            uzenet: uzenetSzoveg
        },
        success: function() {
            $('#uzenetSzoveg').val('');
            betoltBeszelgetes(partner); // Frissítjük az üzeneteket
        },
        error: function() {
            alert('Hiba történt az üzenet küldése közben!');
        }
    });
}

// Billentyű lenyomás figyelése az üzenetküldéshez
$(document).ready(function() {
    $('#uzenetSzoveg').keypress(function(e) {
        if (e.which == 13) { // Enter billentyű
            uzenetKuldes();
            return false;
        }
    });
});

function uzenetTorlese(uzenetId) {
    if (confirm('Biztosan törölni szeretnéd ezt az üzenetet?')) {
        $.ajax({
            url: 'function/uzenet_torles.php',
            type: 'POST',
            data: {
                uzenet_id: uzenetId
            },
            success: function(response) {
                // Frissítjük az üzeneteket
                const partner = $('.beszelgetes-fejlec').data('partner');
                betoltBeszelgetes(partner);
            },
            error: function() {
                alert('Hiba történt az üzenet törlése közben!');
            }
        });
    }
}
// Üzenet módosítása
function uzenetModositasa(uzenetId, eredetiSzoveg) {
    // Létrehozunk egy szövegmezőt a módosításhoz
    const uzenetElem = document.getElementById(`uzenet-${uzenetId}`);
    const szovegElem = uzenetElem.querySelector('.uzenet-szoveg');
    
    // Elmentjük az eredeti szöveget
    const eredeti = szovegElem.textContent;
    
    // Szövegmező létrehozása
    const input = document.createElement('textarea');
    input.value = eredeti;
    input.className = 'modosit-input';
    
    // Gombok létrehozása
    const mentesGomb = document.createElement('button');
    mentesGomb.textContent = 'Mentés';
    mentesGomb.className = 'mentes';
    mentesGomb.onclick = function() {
        uzenetMentes(uzenetId, input.value);
    };
    
    const megsemGomb = document.createElement('button');
    megsemGomb.textContent = 'Mégsem';
    megsemGomb.className = 'megsem';
    megsemGomb.onclick = function() {
        visszaallit(uzenetId, eredeti);
    };
    
    // Cseréljük ki a szöveget a beviteli mezőre
    szovegElem.replaceWith(input);
    
    // Eltüntetjük a módosítás gombot
    uzenetElem.querySelector('.modosit').style.display = 'none';
    
    // Hozzáadjuk a gombokat
    const gombokDiv = document.createElement('div');
    gombokDiv.className = 'modosit-gombok';
    gombokDiv.appendChild(mentesGomb);
    gombokDiv.appendChild(megsemGomb);
    
    uzenetElem.appendChild(gombokDiv);
    input.focus();
}

// Visszaállítás eredeti állapotra
function visszaallit(uzenetId, eredetiSzoveg) {
    const uzenetElem = document.getElementById(`uzenet-${uzenetId}`);
    const input = uzenetElem.querySelector('.modosit-input');
    
    // Új szövegelem létrehozása
    const szovegElem = document.createElement('p');
    szovegElem.className = 'uzenet-szoveg';
    szovegElem.textContent = eredetiSzoveg;
    
    // Visszacseréljük
    input.replaceWith(szovegElem);
    
    // Eltávolítjuk a gombokat
    uzenetElem.querySelector('.modosit-gombok').remove();
    
    // Visszaállítjuk a módosítás gombot
    uzenetElem.querySelector('.modosit').style.display = 'inline-block';
}

// Üzenet mentése
function uzenetMentes(uzenetId, ujSzoveg) {
    if (ujSzoveg.trim() === '') {
        alert('Az üzenet nem lehet üres!');
        return;
    }
    
    $.ajax({
        url: 'function/uzenet_modosit.php',
        type: 'POST',
        data: {
            uzenet_id: uzenetId,
            uj_szoveg: ujSzoveg
        },
        success: function(response) {
            // Frissítjük az üzenetet
            const uzenetElem = document.getElementById(`uzenet-${uzenetId}`);
            const input = uzenetElem.querySelector('.modosit-input');
            
            // Új szövegelem létrehozása
            const szovegElem = document.createElement('p');
            szovegElem.className = 'uzenet-szoveg';
            szovegElem.textContent = ujSzoveg;
            
            // Visszacseréljük
            input.replaceWith(szovegElem);
            
            // Eltávolítjuk a gombokat
            uzenetElem.querySelector('.modosit-gombok').remove();
            
            // Visszaállítjuk a módosítás gombot
            uzenetElem.querySelector('.modosit').style.display = 'inline-block';
            
            // Dátum frissítése
            uzenetElem.querySelector('.uzenet-datum').textContent = 'módosítva: ' + new Date().toLocaleString();
        },
        error: function() {
            alert('Hiba történt az üzenet módosítása közben!');
        }
    });
}
</script>

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

    <div class="beszelgetes-container">
        <div class="beszelgetes-partnerek">
            <h2>Beszélgetések</h2>
            <div class="partner-lista">
                <?php
                if(isset($felhasznalonev)) {
                    $partnerek = beszelgetes_partnerek($felhasznalonev);
                    if($partnerek) {
                        foreach ($partnerek as $partner) {
                            $profilkep = isset($partner['profilkep']) && $partner['profilkep'] ? 
                                'data:image/jpeg;base64,'.base64_encode($partner['profilkep']) : 
                                'kepek/stock.jpg';
                ?>
                            <div class="partner" onclick="betoltBeszelgetes('<?php echo htmlspecialchars($partner['felhasznalonev']); ?>')">
                                <p class="allapot"><?php echo htmlspecialchars($partner['allapot']); ?></p>
                                <img src="<?php echo $profilkep; ?>" alt="Profilkép">
                                <span><?php echo htmlspecialchars($partner['nev']); ?></span>
                            </div>
                <?php 
                        }
                    } else {
                        echo "<p>Nincsenek beszélgetési partnerek.</p>";
                    }
                } else {
                    echo "<p>Kérjük jelentkezzen be a beszélgetések megtekintéséhez.</p>";
                }
                ?>
            </div>
        </div>

        <div class="beszelgetes-ablak">
            <div class="beszelgetes-fejlec">
                <h3>Beszélgetés</h3>
            </div>
            
            <div class="uzenetek" id="uzenetek">
                
            </div>
            
            <div class="uzenet-kuldes">
                <textarea id="uzenetSzoveg" placeholder="Írd ide az üzeneted..."></textarea>
                <button onclick="uzenetKuldes()">Küldés</button>
            </div>
        </div>
    </div>
</body>
</html>