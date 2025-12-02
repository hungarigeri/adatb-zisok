<?php
function adatb_betoltes(){
    $conn = oci_connect('C##F0Q9LP','QxbXDbUyo6' ,'localhost:1521/orania2.inf.u-szeged.hu', 'AL32UTF8');


    if (!$conn) {
        phpinfo();
        $error = oci_error();
        die("Sikertelen csatlakozás: " . $error['message']);
        
    }

    return $conn;
}

function felhasznalo_mentes($felhasznalonev,$nev,$email,$jelszo,$nevnap,$szulinap){
    if(!($conn=adatb_betoltes())) return false;

    $stmt=oci_parse($conn,"INSERT INTO felhasznalo(felhasznalonev, nev, email, jelszo, nevnap, szulinap, ismerosok_szama, allapot) VALUES (:felhasznalonev, :nev, :email, :jelszo, :nevnap, TO_DATE(:szulinap, 'YYYY-MM-DD'), 0, 'Inaktiv')");

    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);
    oci_bind_by_name($stmt, ":nev", $nev);
    oci_bind_by_name($stmt, ":email", $email);
    oci_bind_by_name($stmt, ":jelszo", $jelszo);
    oci_bind_by_name($stmt, ":nevnap", $nevnap);
    oci_bind_by_name($stmt, ":szulinap", $szulinap);

    $siker=oci_execute($stmt);
    if (!$siker) {
        $error = oci_error($stmt);
        die("Mentés sikertelen: " . $error['message']);
    }

    oci_free_statement($stmt);
    oci_close($conn);
    return $siker;
}

function foglalt_felhasznalonev($felhasznalonev){
    if(!($conn=adatb_betoltes())) return false;

    $stmt=oci_parse($conn,"SELECT COUNT(felhasznalonev) AS DB FROM felhasznalo WHERE felhasznalonev=:felhasznalonev");

    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);

    $siker=oci_execute($stmt);
    if (!$siker) {
        $error = oci_error($stmt);
        die("Lekérés sikertelen: " . $error['message']);
    }

    oci_fetch($stmt);
    $count=oci_result($stmt, "DB");

    oci_free_statement($stmt);
    oci_close($conn);
    return $count;
}

function foglalt_email($email){
    if(!($conn=adatb_betoltes())) return false;

    $stmt=oci_parse($conn,"SELECT COUNT(email) AS DB FROM felhasznalo WHERE email=:email");

    oci_bind_by_name($stmt, ":email", $email);

    $siker=oci_execute($stmt);
    if (!$siker) {
        $error = oci_error($stmt);
        die("Lekérés sikertelen: " . $error['message']);
    }


    oci_fetch($stmt);
    $count=oci_result($stmt, "DB");

    oci_free_statement($stmt);
    oci_close($conn);
    return $count;
}

function felhasznalo_adatok($felhasznalonev){
    if (!($conn = adatb_betoltes())) return false;

    $sql = "SELECT felhasznalonev, nev, email, jelszo, regisztracio_datum, nevnap, szulinap, allapot, ismerosok_szama, profilkep FROM felhasznalo WHERE felhasznalonev = :felhasznalonev";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);

    $siker = oci_execute($stmt);
    if (!$siker) {
        $error = oci_error($stmt);
        die("Lekérdezés sikertelen: " . $error['message']);
    }

    $eredmeny = array();
    if ($row = oci_fetch_assoc($stmt)) {
        $eredmeny["felhasználónév"] = $row["FELHASZNALONEV"];
        $eredmeny["név"] = $row["NEV"];
        $eredmeny["email"] = $row["EMAIL"];
        $eredmeny["jelszó"] = $row["JELSZO"];
        $eredmeny["regdátum"] = $row["REGISZTRACIO_DATUM"];
        $eredmeny["névnap"] = $row["NEVNAP"];
        $eredmeny["szülinap"] = $row["SZULINAP"];
        $eredmeny["allapot"] = $row["ALLAPOT"];
        $eredmeny["ismerősszám"] = $row["ISMEROSOK_SZAMA"];
        $_SESSION['felhasznalo']['ismerősszám'] = $row["ISMEROSOK_SZAMA"];
        $eredmeny["profilkép"] = $row["PROFILKEP"];
    }

    oci_free_statement($stmt);
    oci_close($conn);

    return $eredmeny;
}


function felhasznalo_modositas($regi_felhasznalonev, $felhasznalonev, $nev, $email, $jelszo, $nevnap, $szulinap, $admin_e){
    if(!($conn=adatb_betoltes())) return false;

    $sql = "UPDATE felhasznalo 
            SET felhasznalonev = :felhasznalonev, nev = :nev, email = :email, jelszo = :jelszo, nevnap = :nevnap, szulinap = TO_DATE(:szulinap, 'YYYY-MM-DD') 
            WHERE felhasznalonev = :regi_felhasznalonev";

    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);
    oci_bind_by_name($stmt, ":nev", $nev);
    oci_bind_by_name($stmt, ":email", $email);
    oci_bind_by_name($stmt, ":jelszo", $jelszo);
    oci_bind_by_name($stmt, ":nevnap", $nevnap);
    oci_bind_by_name($stmt, ":szulinap", $szulinap);
    oci_bind_by_name($stmt, ":regi_felhasznalonev", $regi_felhasznalonev);

    $siker = oci_execute($stmt);

    if(!$siker) {
        $error = oci_error($stmt);
        die("Hiba frissítéskor: " . $error['message']);
    }

    oci_free_statement($stmt);

    if($admin_e){
        $sql = "UPDATE admin 
            SET adminnev = :adminnev, nev = :nev, email = :email, jelszo = :jelszo, nevnap = :nevnap, szulinap = TO_DATE(:szulinap, 'YYYY-MM-DD')
            WHERE adminnev = :regi_adminnev";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ":adminnev", $felhasznalonev);
        oci_bind_by_name($stmt, ":nev", $nev);
        oci_bind_by_name($stmt, ":email", $email);
        oci_bind_by_name($stmt, ":jelszo", $jelszo);
        oci_bind_by_name($stmt, ":nevnap", $nevnap);
        oci_bind_by_name($stmt, ":szulinap", $szulinap);
        oci_bind_by_name($stmt, ":regi_adminnev", $regi_felhasznalonev);

        $siker = oci_execute($stmt);

        if(!$siker) {
            $error = oci_error($stmt);
            die("Hiba frissítéskor: " . $error['message']);
        }

        oci_free_statement($stmt);
    }


    oci_close($conn);

    return $siker;
}


function profilkep_feltoltes($profilkep, $felhasznalonev, $admin_e) {
    if (!($conn = adatb_betoltes())) return false;

    $lob = oci_new_descriptor($conn, OCI_D_LOB);

    $stmt = oci_parse($conn, "UPDATE felhasznalo SET profilkep = EMPTY_BLOB() WHERE felhasznalonev = :felhasznalonev RETURNING profilkep INTO :kep");

    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);
    oci_bind_by_name($stmt, ":kep", $lob, -1, OCI_B_BLOB);

    $siker = oci_execute($stmt, OCI_DEFAULT);

    if ($siker) {
        $lob->write($profilkep);
        oci_commit($conn);
    } else {
        $error = oci_error($stmt);
        die("Hiba frissítéskor: " . $error['message']);
    }

    oci_free_statement($stmt);


    if ($admin_e) {
        $stmt = oci_parse($conn, "UPDATE admin SET profilkep = EMPTY_BLOB() WHERE adminnev = :adminnev RETURNING profilkep INTO :kep");
        oci_bind_by_name($stmt, ":adminnev", $felhasznalonev);
        oci_bind_by_name($stmt, ":kep", $lob, -1, OCI_B_BLOB);

        $siker = oci_execute($stmt, OCI_DEFAULT);

        if ($siker) {
            $lob->write($profilkep);
            oci_commit($conn);
        } else {
            $error = oci_error($stmt);
            die("Hiba frissítéskor: " . $error['message']);
        }

        oci_free_statement($stmt);
    }

    oci_close($conn);

    return $siker;
}



function felhasznalo_torles($felhasznalonev, $admin_e){
    if (!($conn = adatb_betoltes())) return false;

    $stmt = oci_parse($conn, "DELETE FROM felhasznalo WHERE felhasznalonev = :felhasznalonev");

    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);

    $siker = oci_execute($stmt);
    if (!$siker) {
        $error = oci_error($stmt);
        die("Törlés sikertelen: " . $error['message']);
    }

    oci_free_statement($stmt);

    if($admin_e){
        $stmt = oci_parse($conn, "DELETE FROM admin WHERE adminnev = :adminnev");

        oci_bind_by_name($stmt, ":adminnev", $felhasznalonev);

        $siker = oci_execute($stmt);
        if (!$siker) {
            $error = oci_error($stmt);
            die("Törlés sikertelen: " . $error['message']);
        }

        oci_free_statement($stmt);
    }

    oci_close($conn);
    return $siker;
}

function admin_e($felhasznalonev){
    if(!($conn=adatb_betoltes())) return false;

    $stmt=oci_parse($conn,"SELECT COUNT(adminnev) AS DB FROM admin WHERE adminnev=:adminnev");

    oci_bind_by_name($stmt, ":adminnev", $felhasznalonev);

    $siker=oci_execute($stmt);
    if(!$siker) die(oci_error($conn));


    oci_fetch($stmt);
    $count=oci_result($stmt, "DB");

    oci_free_statement($stmt);
    oci_close($conn);
    return $count;
}

function allapotfrissites($felhasznalonev){
    if(!($conn=adatb_betoltes())) return false;

    $sql = 'BEGIN allapot_frissites(:felhasznalonev); END;';
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ':felhasznalonev', $felhasznalonev);

    $siker=oci_execute($stmt);
    if(!$siker) die(oci_error($conn));

    oci_free_statement($stmt);
    oci_close($conn);
    return $siker;
}

function honap_szulinapok($felhasznalonev,$honap){
    if(!($conn=adatb_betoltes())) return false;


    $sql="SELECT TO_CHAR(f.szulinap, 'MONTH', 'NLS_DATE_LANGUAGE = HUNGARIAN') AS honap, 
            f. szulinap AS szulinap,
            f.felhasznalonev AS felhasznalonev
        FROM ISMEROS_KERELEM k
        JOIN FELHASZNALO f ON f.felhasznalonev = 
            CASE
                WHEN k.felhasznalonev = :felhasznalonev THEN k.fogado
                ELSE k.felhasznalonev
            END
        WHERE k.allapot = 'Elfogadva' 
        AND :felhasznalonev IN (k.felhasznalonev, k.fogado) 
        AND TRIM(TO_CHAR(f.szulinap, 'MONTH', 'NLS_DATE_LANGUAGE = HUNGARIAN'))=:honap";

    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ':felhasznalonev', $felhasznalonev);
    oci_bind_by_name($stmt, ':honap', $honap);

    $siker=oci_execute($stmt);
    if (!$siker) {
        $error = oci_error($stmt);
        die("Lekérés sikertelen: " . $error['message']);
    }

    $eredmeny = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $eredmeny[] = $row;
    }

    oci_free_statement($stmt);
    oci_close($conn);

    return $eredmeny ?: null;
}

/**ismerős kerelem tábla */
//adatok beolvas
function felhasznalo_baratkerelemek($felhasznalonev) {
    if (!($conn = adatb_betoltes())) return false;

    $sql = "SELECT * FROM ismeros_kerelem 
            WHERE (( fogado = :felhasznalonev)
            AND ALLAPOT != 'Elfogadva')";
   
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);

    $siker = oci_execute($stmt);
    if (!$siker) {
        $error = oci_error($stmt);
        die("Lekérdezés sikertelen: " . $error['message']);
    }

    $kerelemek = array();
    while ($row = oci_fetch_assoc($stmt)) {
        $kerelemek[] = $row;
    }
   
    oci_free_statement($stmt);
    oci_close($conn);

    return $kerelemek;
}
 // Összes felhasználó lekérdezése, kivéve a bejelentkezettet és azok, akikkel már van kapcsolat
function felhasznalo_jeloleskerelemek($felhasznalonev) {
    if (!($conn = adatb_betoltes())) return false;

    $sql = "SELECT f.felhasznalonev, f.nev 
            FROM felhasznalo f
            WHERE f.felhasznalonev != :felhasznalonev
            AND f.felhasznalonev NOT IN (
                SELECT ik.felhasznalonev 
                FROM ismeros_kerelem ik 
                WHERE ik.fogado = :felhasznalonev
                UNION
                SELECT ik.fogado 
                FROM ismeros_kerelem ik 
                WHERE ik.felhasznalonev = :felhasznalonev
            )";
   
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);

    $siker = oci_execute($stmt);
    if (!$siker) {
        $error = oci_error($stmt);
        die("Lekérdezés sikertelen: " . $error['message']);
    }

    $felhasznalok = array();
    while ($row = oci_fetch_assoc($stmt)) {
        $felhasznalok[] = $row;
    }
   
    oci_free_statement($stmt);
    oci_close($conn);

    return $felhasznalok;
}

//felhasználó bejelőlése
function jelolesKuldese($felhasznalonev, $fogado) {
    if (!($conn = adatb_betoltes())) return false;

    // 1. Az új ISMEROS_KERELEM_ID meghatározása
    $sql_id = "SELECT NVL(MAX(ISMEROS_KERELEM_ID), 0) + 1 AS UJ_ID FROM ISMEROS_KERELEM";
    $stmt_id = oci_parse($conn, $sql_id);
    
    if (!oci_execute($stmt_id)) {
        $error = oci_error($stmt_id);
        die("ID lekérdezés sikertelen: " . $error['message']);
    }
    
    oci_fetch($stmt_id);
    $uj_id = oci_result($stmt_id, "UJ_ID");
    oci_free_statement($stmt_id);

    // 2. A jelölés létrehozása
    $sql = "BEGIN JELOLES_HOZZAADAS(:id, :fogado, :felhasznalonev); END;";
    
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":id", $uj_id);
    oci_bind_by_name($stmt, ":fogado", $fogado);
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);

    $siker = oci_execute($stmt);
    if (!$siker) {
        $error = oci_error($stmt);
        die("Jelölés sikertelen: " . $error['message']);
    }

    oci_free_statement($stmt);
    oci_close($conn);

    return $siker;
}
function baratjeloles_torlese($kerelem_id, $felhasznalonev) {
    if (!($conn = adatb_betoltes())) return false;

    $sql = "DELETE FROM ismeros_kerelem 
            WHERE ismeros_kerelem_id = :kerelem_id 
            AND (felhasznalonev = :felhasznalonev OR fogado = :felhasznalonev)";
    
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":kerelem_id", $kerelem_id);
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);

    $siker = oci_execute($stmt);
    if (!$siker) {
        $error = oci_error($stmt);
        throw new Exception("Jelölés törlése sikertelen: " . $error['message']);
    }

    oci_free_statement($stmt);
    oci_close($conn);

    return $siker;
}
function baratjeloles_elfogadasa($kerelem_id, $felhasznalonev) {
    if (!($conn = adatb_betoltes())) return false;

    $sql = "UPDATE ismeros_kerelem 
            SET ALLAPOT = 'Elfogadva'
            WHERE ismeros_kerelem_id = :kerelem_id 
            AND (felhasznalonev = :felhasznalonev OR fogado = :felhasznalonev)";
    
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":kerelem_id", $kerelem_id);
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);

    $siker = oci_execute($stmt);
    if (!$siker) {
        $error = oci_error($stmt);
        throw new Exception("Jelölés fogadása sikertelen: " . $error['message']);
    }

    oci_free_statement($stmt);
    oci_close($conn);

    return $siker;
}
//cseveges
/**
 * Beszélgetés partnerek lekérdezése (akikkel elfogadott kapcsolat van)
 */
function beszelgetes_partnerek($felhasznalonev) {
    if (!($conn = adatb_betoltes())) return false;

    $sql = "SELECT 
                f.felhasznalonev, 
                f.nev, 
                f.profilkep,
                f.allapot,
                (SELECT MAX(pu.kuldes_datuma) 
                 FROM privat_uzenet pu 
                 WHERE (pu.felhasznalonev = f.felhasznalonev AND pu.cimzett = :felhasznalonev)
                 OR (pu.felhasznalonev = :felhasznalonev AND pu.cimzett = f.felhasznalonev)) as utolso_uzenet
            FROM 
                felhasznalo f
            JOIN 
                ismeros_kerelem ik ON 
                (ik.felhasznalonev = f.felhasznalonev OR ik.fogado = f.felhasznalonev)
            WHERE 
                ik.allapot = 'Elfogadva'
                AND f.felhasznalonev != :felhasznalonev
                AND (:felhasznalonev IN (ik.felhasznalonev, ik.fogado))
            ORDER BY utolso_uzenet DESC";

    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);

    $siker = oci_execute($stmt);
    if (!$siker) {
        $error = oci_error($stmt);
        throw new Exception("Lekérdezés sikertelen: " . $error['message']);
    }

    $partnerek = array();
    while ($row = oci_fetch_assoc($stmt)) {
        $partnerek[] = array(
            'felhasznalonev' => $row['FELHASZNALONEV'],
            'nev' => $row['NEV'],
            'profilkep' => $row['PROFILKEP'],
            'utolso_uzenet' => $row['UTOLSO_UZENET'],
            'allapot' => $row["ALLAPOT"]
        );
    }

    oci_free_statement($stmt);
    oci_close($conn);

    return $partnerek;
}
//üzenetek betöltése 
function beszelgetes_uzenetek($felhasznalonev, $partner) {
    if (!($conn = adatb_betoltes())) return false;

    $sql = "SELECT * FROM PRIVAT_UZENET 
            WHERE (felhasznalonev = :felhasznalonev AND cimzett = :partner)
            OR (felhasznalonev = :partner AND cimzett = :felhasznalonev)
            ORDER BY kuldes_datuma ASC";
    
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);
    oci_bind_by_name($stmt, ":partner", $partner);

    $siker = oci_execute($stmt);
    if (!$siker) {
        $error = oci_error($stmt);
        throw new Exception("Lekérdezés sikertelen: " . $error['message']);
    }

    $uzenetek = array();
    while ($row = oci_fetch_assoc($stmt)) {
        $uzenetek[] = $row;
    }

    oci_free_statement($stmt);
    oci_close($conn);

    return $uzenetek;
}

/** csoportok */
//csoportok megjelnítése
function csoportok_lekerdezese($felhasznalonev) {
    if (!($conn = adatb_betoltes())) return false;
    
    $sql = "SELECT c.*
            FROM CSOPORT c
            WHERE c.CSOPORT_ID NOT IN (
                SELECT ct.CSOPORT_ID
                FROM CSOPORT_TAGJA ct
                WHERE ct.FELHASZNALONEV = :felhasznalonev
            )
            ORDER BY c.LETREHOZAS_DATUMA DESC";
            
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);
    
    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        die("Lekérdezés sikertelen: " . $error['message']);
    }
    
    $csoportok = array();
    while ($row = oci_fetch_assoc($stmt)) {
        $csoportok[] = $row;
    }
    
    oci_free_statement($stmt);
    oci_close($conn);
    
    return $csoportok;
}
//csak sajat csoport megjelenitese
function sajat_csoportok_lekerdezese($felhasznalonev) {
    if (!($conn = adatb_betoltes())) return false;
    
    $sql = "SELECT c.*
            FROM CSOPORT c
            INNER JOIN CSOPORT_TAGJA ct ON c.CSOPORT_ID = ct.CSOPORT_ID
            WHERE ct.FELHASZNALONEV = :felhasznalonev
            ORDER BY c.LETREHOZAS_DATUMA DESC";
    
    $stmt = oci_parse($conn, $sql);

    // Paraméter kötése (bindelés), hogy biztonságos legyen
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);
    
    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        die("Lekérdezés sikertelen: " . $error['message']);
    }
    
    $csoportok = array();
    while ($row = oci_fetch_assoc($stmt)) {
        $csoportok[] = $row;
    }
    
    oci_free_statement($stmt);
    oci_close($conn);
    
    return $csoportok;
}
// csoportba csatlakozás 
function csoport_csatlakozas($felhasznalonev, $csoport_id) {
    if (!($conn = adatb_betoltes())) return false;

    // Check if user is already in group
    $check_sql = "BEGIN
    IF CSOPORTBA_VANE(:csoport_id, :felhasznalonev) = 0 THEN
        CSOPORTBA_CSATLAKOZAS(:csoport_id, :felhasznalonev);
    END IF;
END;";
    $check_stmt = oci_parse($conn, $check_sql);
    oci_bind_by_name($check_stmt, ":felhasznalonev", $felhasznalonev);
    oci_bind_by_name($check_stmt, ":csoport_id", $csoport_id);
    
    if (!oci_execute($check_stmt)) {
        oci_close($conn);
        return false;
    }
    
    oci_fetch($check_stmt);
    $count = oci_result($check_stmt, "DB");
    oci_free_statement($check_stmt);
    
    if ($count > 0) {
        oci_close($conn);
        return true; // Already a member
    }

    // Insert into group
    $insert_sql = "BEGIN CSOPORTBA_CSATLAKOZAS(:csoport_id, :felhasznalonev); END;";
    $insert_stmt = oci_parse($conn, $insert_sql);
    oci_bind_by_name($insert_stmt, ":csoport_id", $csoport_id);
    oci_bind_by_name($insert_stmt, ":felhasznalonev", $felhasznalonev);
    
    $siker = oci_execute($insert_stmt);
    
    oci_free_statement($insert_stmt);
    oci_close($conn);
}

function sajat_csoportok_törlése($felhasznalonev) {
    if (!($conn = adatb_betoltes())) return false;
    
    $sql = "SELECT c.*
            FROM CSOPORT c
            INNER JOIN CSOPORTOT_KEZEL ct ON c.CSOPORT_ID = ct.CSOPORT_ID
            WHERE ct.FELHASZNALONEV = :felhasznalonev
            ORDER BY c.LETREHOZAS_DATUMA DESC";
    
    $stmt = oci_parse($conn, $sql);

    // Paraméter kötése (bindelés), hogy biztonságos legyen
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);
    
    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        die("Lekérdezés sikertelen: " . $error['message']);
    }
    
    $csoportok = array();
    while ($row = oci_fetch_assoc($stmt)) {
        $csoportok[] = $row;
    }
    
    oci_free_statement($stmt);
    oci_close($conn);
    
    return $csoportok;
}
function sajat_csoport_törlése($csoport_id, $felhasznalonev) {
    if (!($conn = adatb_betoltes())) return false;
    
    // First verify the user has permission to delete this group
    $check_sql = "SELECT COUNT(*) AS DB FROM CSOPORTOT_KEZEL 
                  WHERE CSOPORT_ID = :csoport_id AND FELHASZNALONEV = :felhasznalonev";
    $check_stmt = oci_parse($conn, $check_sql);
    oci_bind_by_name($check_stmt, ":csoport_id", $csoport_id);
    oci_bind_by_name($check_stmt, ":felhasznalonev", $felhasznalonev);
    
    if (!oci_execute($check_stmt)) {
        $error = oci_error($check_stmt);
        die("Permission check failed: " . $error['message']);
    }
    
    oci_fetch($check_stmt);
    $count = oci_result($check_stmt, "DB");
    oci_free_statement($check_stmt);
    
    if ($count == 0) {
        oci_close($conn);
        return false; // User doesn't have permission to delete this group
    }

    // Delete the group (Oracle will cascade delete related records due to foreign key constraints)
    $delete_sql = "DELETE FROM CSOPORT WHERE CSOPORT_ID = :csoport_id";
    $delete_stmt = oci_parse($conn, $delete_sql);
    oci_bind_by_name($delete_stmt, ":csoport_id", $csoport_id);
    
    $siker = oci_execute($delete_stmt);
    
    if (!$siker) {
        $error = oci_error($delete_stmt);
        die("Delete failed: " . $error['message']);
    }
    
    oci_free_statement($delete_stmt);
    oci_close($conn);
    
    return $siker;
}

//**Rendszerüzenet */
//rendszer üzenetek lekérese a megfelelő felhasználóhoz   
function get_rendszeruzenetek($felhasznalonev) {
    $conn = adatb_betoltes();
    $sql = "SELECT * FROM PRIVAT_UZENET 
            WHERE CIMZETT = :felhasznalonev AND FELHASZNALONEV = 'Rendszer'
            ORDER BY KULDES_DATUMA DESC";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);
    oci_execute($stmt);

    $uzenetek = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $uzenetek[] = $row;
    }

    oci_free_statement($stmt);
    oci_close($conn);
    return $uzenetek;
}
//jelentések megjelenitese
function felhasznalo_jelentesei($felhasznalonev) {
    $conn = adatb_betoltes();
    $sql = "SELECT j.JELENTES_ID, j.LEIRAS, j.LETREHOZAS_DATUMA, 
                   j.STATUSZ as ALLAPOT, j.TIPUS as TARGY_TIPUS, j.JELENTETT
            FROM JELENTES j
            WHERE j.FELHASZNALONEV = :felhasznalonev
            ORDER BY j.LETREHOZAS_DATUMA DESC";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":felhasznalonev", $felhasznalonev);
    oci_execute($stmt);

    $jelentesek = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $jelentesek[] = [
            'JELENTES_ID' => $row['JELENTES_ID'],
            'LEIRAS' => $row['LEIRAS'],
            'DATUM' => $row['LETREHOZAS_DATUMA'],
            'ALLAPOT' => $row['ALLAPOT'],
            'TARGY_TIPUS' => $row['TARGY_TIPUS'],
            'JELENTETT' => $row['JELENTETT'] // Using JELENTETT instead of TARGY_ID
        ];
    }

    oci_free_statement($stmt);
    oci_close($conn);

    return $jelentesek;
}