<?php
include "database_contr.php";
function jelentes_nelkuli_adminok() {
    if (!($conn = adatb_betoltes())) return false;

    $sql = "SELECT COUNT(*) AS aktiv_adminok_jelentes_nelkul
            FROM admin
            WHERE allapot = 'Aktív'
              AND NOT EXISTS (
                SELECT jelentes_id
                FROM jelentes
                WHERE jelentes.adminnev = admin.adminnev
              )";

    $stmt = oci_parse($conn, $sql);

    $siker=oci_execute($stmt);
    if (!$siker) {
        $error = oci_error($stmt);
        die("Lekérés sikertelen: " . $error['message']);
    }

    $row = oci_fetch_assoc($stmt);
    $szam = $row['AKTIV_ADMINOK_JELENTES_NELKUL'];

    oci_free_statement($stmt);
    oci_close($conn);

    return $szam;
}



function csoport_legidosebb($csoportid){
    if (!($conn = adatb_betoltes())) return false;


    $sql = "SELECT felhasznalo.felhasznalonev, felhasznalo.szulinap FROM csoport
            JOIN csoport_tagja ON csoport.csoport_id=csoport_tagja.csoport_ID
            JOIN felhasznalo ON csoport_tagja.felhasznalonev=felhasznalo.felhasznalonev
            WHERE csoport_tagja.csoport_id=:csoportid 
            AND felhasznalo.szulinap=
            (SELECT MIN(f.szulinap)
            FROM csoport_tagja cs
            JOIN felhasznalo f ON cs.felhasznalonev = f.felhasznalonev
            WHERE cs.csoport_id = :csoportid)";

    $stmt=oci_parse($conn,$sql);

    oci_bind_by_name($stmt,":csoportid",$csoportid);

    $siker=oci_execute($stmt);
    if (!$siker) {
        $error = oci_error($stmt);
        die("Lekérés sikertelen: " . $error['message']);
    }

    $row = oci_fetch_assoc($stmt);

    oci_free_statement($stmt);
    oci_close($conn);

    return $row ?: null;
}


function csoport_letszam(){
    if (!($conn = adatb_betoltes())) return false;


    $sql = "SELECT csoport.nev AS csoportnev, COUNT(csoport_tagja.felhasznalonev) AS letszam
            FROM csoport
            JOIN csoport_tagja ON csoport.csoport_id = csoport_tagja.csoport_id
            GROUP BY csoport.nev";

    $stmt=oci_parse($conn,$sql);

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


function ismerosok_szuletesnapjai($username){
    if(!($conn=adatb_betoltes())) return false;


    $sql = "SELECT 
            TO_CHAR(f.szulinap, 'MONTH', 'NLS_DATE_LANGUAGE = HUNGARIAN') AS honap, 
            COUNT(k.ismeros_kerelem_id) AS szuletesnapok
            FROM ISMEROS_KERELEM k
            JOIN FELHASZNALO f ON f.felhasznalonev = 
                CASE
                    WHEN k.felhasznalonev = :felhasznalonev THEN k.fogado
                    ELSE k.felhasznalonev
                END
            WHERE k.allapot = 'Elfogadva' 
            AND :felhasznalonev IN (k.felhasznalonev, k.fogado) 
            AND f.szulinap IS NOT NULL
            GROUP BY 
                TO_CHAR(f.szulinap, 'MONTH', 'NLS_DATE_LANGUAGE = HUNGARIAN'),
                TO_NUMBER(TO_CHAR(f.szulinap, 'MM'))
            ORDER BY TO_NUMBER(TO_CHAR(f.szulinap, 'MM'))";


    $stmt=oci_parse($conn,$sql);

    oci_bind_by_name($stmt,":felhasznalonev",$username);

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


function csoporttagok_osszismeros(){
    if(!($conn=adatb_betoltes())) return false;


    $sql = "SELECT CSOPORT.nev AS csoportnev, SUM(FELHASZNALO.ISMEROSOK_SZAMA) AS ismerosok
            FROM CSOPORT
            JOIN CSOPORT_TAGJA ON CSOPORT.CSOPORT_ID=CSOPORT_TAGJA.CSOPORT_ID
            JOIN FELHASZNALO ON CSOPORT_TAGJA.felhasznalonev=FELHASZNALO.felhasznalonev
            GROUP BY CSOPORT.nev
            ORDER BY ismerosok";


    $stmt=oci_parse($conn,$sql);

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

?>



