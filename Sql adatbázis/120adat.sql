--------------------------------------------------------
--  File created - csütörtök-május-15-2025   
--------------------------------------------------------
--------------------------------------------------------
--  DDL for Sequence BEJEGYZES_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "BEJEGYZES_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 46 NOCACHE  NOORDER  NOCYCLE  NOKEEP  NOSCALE  GLOBAL ;
--------------------------------------------------------
--  DDL for Sequence JELENTES_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "JELENTES_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE  NOORDER  NOCYCLE  NOKEEP  NOSCALE  GLOBAL ;
--------------------------------------------------------
--  DDL for Sequence KEP_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "KEP_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 20 NOCACHE  NOORDER  NOCYCLE  NOKEEP  NOSCALE  GLOBAL ;
--------------------------------------------------------
--  DDL for Sequence KOMMENT_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "KOMMENT_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 39 NOCACHE  NOORDER  NOCYCLE  NOKEEP  NOSCALE  GLOBAL ;
--------------------------------------------------------
--  DDL for Table ADMIN
--------------------------------------------------------

  CREATE TABLE "ADMIN" 
   (	"ADMINNEV" VARCHAR2(255 BYTE), 
	"NEV" VARCHAR2(255 BYTE), 
	"JELSZO" VARCHAR2(255 BYTE), 
	"EMAIL" VARCHAR2(60 BYTE), 
	"REGISZTRACIO_DATUM" DATE, 
	"NEVNAP" CHAR(6 BYTE), 
	"ALLAPOT" VARCHAR2(10 BYTE), 
	"SZULINAP" DATE, 
	"PROFILKEP" BLOB, 
	"ISMEROSOK_SZAMA" NUMBER(4,0)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" 
 LOB ("PROFILKEP") STORE AS SECUREFILE (
  TABLESPACE "USERS" ENABLE STORAGE IN ROW CHUNK 8192
  NOCACHE LOGGING  NOCOMPRESS  KEEP_DUPLICATES 
  STORAGE(INITIAL 106496 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)) ;
--------------------------------------------------------
--  DDL for Table BEJEGYZES
--------------------------------------------------------

  CREATE TABLE "BEJEGYZES" 
   (	"BEJEGYZES_ID" NUMBER(4,0), 
	"LEIRAS" VARCHAR2(255 BYTE), 
	"FELTOLTES_IDEJE" DATE DEFAULT SYSDATE, 
	"SZOVEG" VARCHAR2(255 BYTE), 
	"FELHASZNALONEV" VARCHAR2(255 BYTE)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Table CSOPORT
--------------------------------------------------------

  CREATE TABLE "CSOPORT" 
   (	"CSOPORT_ID" NUMBER(4,0), 
	"NEV" VARCHAR2(60 BYTE), 
	"LEIRAS" VARCHAR2(255 BYTE), 
	"LETREHOZAS_DATUMA" DATE DEFAULT SYSDATE
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Table CSOPORT_BEJEGYZES
--------------------------------------------------------

  CREATE TABLE "CSOPORT_BEJEGYZES" 
   (	"BEJEGYZES_ID" NUMBER(4,0), 
	"CSOPORT_ID" NUMBER(4,0)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Table CSOPORTOT_KEZEL
--------------------------------------------------------

  CREATE TABLE "CSOPORTOT_KEZEL" 
   (	"FELHASZNALONEV" VARCHAR2(255 BYTE), 
	"CSOPORT_ID" NUMBER(4,0)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Table CSOPORT_TAGJA
--------------------------------------------------------

  CREATE TABLE "CSOPORT_TAGJA" 
   (	"CSOPORT_ID" NUMBER(4,0), 
	"FELHASZNALONEV" VARCHAR2(255 BYTE)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Table FELHASZNALO
--------------------------------------------------------

  CREATE TABLE "FELHASZNALO" 
   (	"FELHASZNALONEV" VARCHAR2(255 BYTE), 
	"NEV" VARCHAR2(255 BYTE), 
	"JELSZO" VARCHAR2(255 BYTE), 
	"EMAIL" VARCHAR2(60 BYTE), 
	"REGISZTRACIO_DATUM" DATE DEFAULT SYSDATE, 
	"NEVNAP" CHAR(6 BYTE), 
	"ALLAPOT" VARCHAR2(10 BYTE), 
	"SZULINAP" DATE, 
	"PROFILKEP" BLOB, 
	"ISMEROSOK_SZAMA" NUMBER(4,0)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" 
 LOB ("PROFILKEP") STORE AS SECUREFILE (
  TABLESPACE "USERS" ENABLE STORAGE IN ROW CHUNK 8192
  NOCACHE LOGGING  NOCOMPRESS  KEEP_DUPLICATES 
  STORAGE(INITIAL 106496 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)) ;
--------------------------------------------------------
--  DDL for Table ISMEROS_KERELEM
--------------------------------------------------------

  CREATE TABLE "ISMEROS_KERELEM" 
   (	"ISMEROS_KERELEM_ID" NUMBER(4,0), 
	"ALLAPOT" VARCHAR2(255 BYTE), 
	"LETREHOZAS_DATUMA" DATE DEFAULT SYSDATE, 
	"FOGADO" VARCHAR2(255 BYTE), 
	"FELHASZNALONEV" VARCHAR2(255 BYTE)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Table JELENTES
--------------------------------------------------------

  CREATE TABLE "JELENTES" 
   (	"JELENTES_ID" NUMBER(4,0), 
	"TIPUS" VARCHAR2(1000 BYTE), 
	"JELENTETT" VARCHAR2(255 BYTE), 
	"STATUSZ" VARCHAR2(255 BYTE), 
	"LETREHOZAS_DATUMA" DATE DEFAULT SYSDATE, 
	"LEIRAS" VARCHAR2(1000 BYTE), 
	"ADMINNEV" VARCHAR2(255 BYTE), 
	"FELHASZNALONEV" VARCHAR2(255 BYTE)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Table KEPEK
--------------------------------------------------------

  CREATE TABLE "KEPEK" 
   (	"KEP_ID" NUMBER(4,0), 
	"FOTO" BLOB, 
	"BEJEGYZES_ID" NUMBER(4,0)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" 
 LOB ("FOTO") STORE AS SECUREFILE (
  TABLESPACE "USERS" ENABLE STORAGE IN ROW CHUNK 8192
  NOCACHE LOGGING  NOCOMPRESS  KEEP_DUPLICATES 
  STORAGE(INITIAL 106496 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)) ;
--------------------------------------------------------
--  DDL for Table KOMMENT
--------------------------------------------------------

  CREATE TABLE "KOMMENT" 
   (	"LETREHOZAS_DATUMA" DATE DEFAULT SYSDATE, 
	"KOMMENT" VARCHAR2(255 BYTE), 
	"FELHASZNALONEV" VARCHAR2(255 BYTE), 
	"BEJEGYZES_ID" NUMBER, 
	"KOMMENT_ID" NUMBER(4,0)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Table LAJKOLAS
--------------------------------------------------------

  CREATE TABLE "LAJKOLAS" 
   (	"FELHASZNALONEV" VARCHAR2(255 BYTE), 
	"BEJEGYZES_ID" NUMBER(4,0)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Table PRIVAT_UZENET
--------------------------------------------------------

  CREATE TABLE "PRIVAT_UZENET" 
   (	"UZENET_ID" NUMBER(4,0), 
	"SZOVEG" VARCHAR2(1000 BYTE), 
	"CIMZETT" VARCHAR2(255 BYTE), 
	"KULDES_DATUMA" DATE DEFAULT SYSDATE, 
	"FELHASZNALONEV" VARCHAR2(255 BYTE)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
REM INSERTING into ADMIN
SET DEFINE OFF;
Insert into ADMIN (ADMINNEV,NEV,JELSZO,EMAIL,REGISZTRACIO_DATUM,NEVNAP,ALLAPOT,SZULINAP,ISMEROSOK_SZAMA) values ('varga_aura','Varga Laura','$2y$10$KIZ4oOr.1JXS/VGgJnqA6eTWqRN3yvmD36Mi8bJ1URRBA57hEUyb.','varga.laura@example.com',to_date('25-MÁRC. -23','RR-MON-DD'),'02.28.','Inaktív',to_date('93-JÚL.  -17','RR-MON-DD'),'15');
Insert into ADMIN (ADMINNEV,NEV,JELSZO,EMAIL,REGISZTRACIO_DATUM,NEVNAP,ALLAPOT,SZULINAP,ISMEROSOK_SZAMA) values ('wolfkiller','Farkas Zoltán','$2y$10$KIZ4oOr.1JXS/VGgJnqA6eTWqRN3yvmD36Mi8bJ1URRBA57hEUyb.','farkas.zoltan@example.com',to_date('25-MÁRC. -23','RR-MON-DD'),'05.09.','Inaktív',to_date('97-NOV.  -11','RR-MON-DD'),'25');
Insert into ADMIN (ADMINNEV,NEV,JELSZO,EMAIL,REGISZTRACIO_DATUM,NEVNAP,ALLAPOT,SZULINAP,ISMEROSOK_SZAMA) values ('kovacska','Kovács István','$2y$10$KIZ4oOr.1JXS/VGgJnqA6eTWqRN3yvmD36Mi8bJ1URRBA57hEUyb.','kovacs.istvan@example.com',to_date('25-MÁRC. -23','RR-MON-DD'),'09.13.','Inaktív',to_date('90-JAN.  -25','RR-MON-DD'),'20');
REM INSERTING into BEJEGYZES
SET DEFINE OFF;
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('38','ada',to_date('25-MÁJ.  -01','RR-MON-DD'),'adaa','petya12');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('5','kfc',to_date('25-ÁPR.  -26','RR-MON-DD'),'nagyon szeretem a kfc-t','nyolca');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('3','Nekem is tetszik minden',to_date('25-ÁPR.  -26','RR-MON-DD'),'Körülbelül mindennel meg vagyok elégedve','varga_aura');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('4','Embereket nem szeretem',to_date('25-ÁPR.  -26','RR-MON-DD'),'Valamiért nem szeretem az embereket túl közek','julcsi');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('1','Minden a legnagyobb rendben van ',to_date('25-ÁPR.  -26','RR-MON-DD'),'Szerintem minden a legnagyobb rendben van','tothgabi');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('2','Szeretem a logót',to_date('25-ÁPR.  -26','RR-MON-DD'),'Tényleg nagyon szeretem','tothgabi');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('43','tarolt123',to_date('25-MÁJ.  -01','RR-MON-DD'),'123','petya12');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('44','Imádom a tavaszt',to_date('25-MÁRC. -29','RR-MON-DD'),'Minden zöldül, fantasztikus idő van!','germanklau');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('45','Ma esett az eső',to_date('25-MÁJ.  -02','RR-MON-DD'),'Szeretem, amikor csendben esik.','szabeszka12');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('46','Új játék jelent meg',to_date('25-MÁJ.  -01','RR-MON-DD'),'Alig várom, hogy kipróbáljam!','gamergirl##');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('47','Kávé nélkül nem megy',to_date('25-ÁPR.  -28','RR-MON-DD'),'Ma is csak a koffein tart életben.','kovacska');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('48','Hétfő van',to_date('25-MÁJ.  -05','RR-MON-DD'),'Új hét, új lehetőségek. Vagy mégsem?','julcsi');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('49','Fárasztó nap volt',to_date('25-ÁPR.  -30','RR-MON-DD'),'Már csak a pihenés maradt.','bélabá');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('50','Motiváció hiány',to_date('25-MÁJ.  -03','RR-MON-DD'),'Kéne valami, ami feldob...','gyilkos_mester');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('51','Jó könyvet olvasok',to_date('25-MÁJ.  -04','RR-MON-DD'),'A történet teljesen beszippantott!','varga_aura');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('52','Éjszakázás',to_date('25-ÁPR.  -29','RR-MON-DD'),'Megint túl sokáig játszottam.','ninjasniper');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('53','Kutyasétáltatás',to_date('25-MÁJ.  -05','RR-MON-DD'),'A kutyám jobban élvezi a sétát, mint én.','wolfkiller');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('54','Kávé + zene = tökély',to_date('25-ÁPR.  -30','RR-MON-DD'),'Így kezdődik jól a nap.','szenteltviz');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('55','Megcsináltam a feladatot!',to_date('25-MÁJ.  -01','RR-MON-DD'),'Ez a bejegyzés is bizonyítja.','tothgabi');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('56','Futás reggel',to_date('25-MÁRC. -30','RR-MON-DD'),'6 km megvolt, indulhat a nap!','lakatos');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('57','Nyugalom',to_date('25-MÁJ.  -04','RR-MON-DD'),'Csak ültem és hallgattam a madarakat.','csunyauser');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('58','Péntek van!',to_date('25-MÁJ.  -02','RR-MON-DD'),'Hétvége, végre egy kis pihenés.','germanklau');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('59','Új receptet próbáltam',to_date('25-ÁPR.  -27','RR-MON-DD'),'Nem lett rossz, de legközelebb máshogy csinálom.','nyolca');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('60','Valami készül...',to_date('25-MÁJ.  -05','RR-MON-DD'),'Hamarosan nagy bejelentés jön.','petya12');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('61','Nem találom a kulcsom',to_date('25-ÁPR.  -29','RR-MON-DD'),'Valószínűleg megint a kabátomban van.','szabeszka12');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('62','Alvás idő',to_date('25-MÁJ.  -05','RR-MON-DD'),'Ez volt mára, jó éjt!','kovacska');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('63','Meglepetés ajándék',to_date('25-MÁJ.  -03','RR-MON-DD'),'Valaki hagyott valamit az ajtóm előtt.','varga_aura');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('64','Elképesztő kapás',to_date('25-MÁJ.  -15','RR-MON-DD'),'Elképesztően nagy halat fogtam ma van vagy 10kg','bélabá');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('65','Fekete cica',to_date('25-MÁRC. -12','RR-MON-DD'),'Ma átszaldt előttem egy feket cica az úton ti is hisztek ebben hogy balszerencsém lesz? ','varga_aura');
Insert into BEJEGYZES (BEJEGYZES_ID,LEIRAS,FELTOLTES_IDEJE,SZOVEG,FELHASZNALONEV) values ('66','Festmény',to_date('25-JAN.  -08','RR-MON-DD'),'Nektek se tetszenek Leonardo da Vinci festményei','nyolca');
REM INSERTING into CSOPORT
SET DEFINE OFF;
Insert into CSOPORT (CSOPORT_ID,NEV,LEIRAS,LETREHOZAS_DATUMA) values ('1','Sport Klub','Egy sportklub célja a versenysportok és szabadidős tevékenységek népszerűsítése.',to_date('25-MÁRC. -23','RR-MON-DD'));
Insert into CSOPORT (CSOPORT_ID,NEV,LEIRAS,LETREHOZAS_DATUMA) values ('2','Művészeti Csoport','A csoport célja különböző művészeti ágak népszerűsítése.',to_date('25-MÁRC. -23','RR-MON-DD'));
Insert into CSOPORT (CSOPORT_ID,NEV,LEIRAS,LETREHOZAS_DATUMA) values ('3','Környezettudatos Társaság','A csoport célja a fenntarthatóság és környezettudatosság előmozdítása.',to_date('25-MÁRC. -23','RR-MON-DD'));
Insert into CSOPORT (CSOPORT_ID,NEV,LEIRAS,LETREHOZAS_DATUMA) values ('4','Programozói Csoport','A csoport célja a programozás és informatikai ismeretek fejlesztése.',to_date('25-MÁRC. -23','RR-MON-DD'));
Insert into CSOPORT (CSOPORT_ID,NEV,LEIRAS,LETREHOZAS_DATUMA) values ('5','Tudományos Egyesület','A csoport célja tudományos kutatások és fejlesztések támogatása.',to_date('25-MÁRC. -23','RR-MON-DD'));
Insert into CSOPORT (CSOPORT_ID,NEV,LEIRAS,LETREHOZAS_DATUMA) values ('6','Kiscicák','Imádom a cicákat!',to_date('25-MÁRC. -23','RR-MON-DD'));
Insert into CSOPORT (CSOPORT_ID,NEV,LEIRAS,LETREHOZAS_DATUMA) values ('7','Horgászok klubbja','A legjobb dolog a horgászás!',to_date('25-MÁRC. -23','RR-MON-DD'));
REM INSERTING into CSOPORT_BEJEGYZES
SET DEFINE OFF;
Insert into CSOPORT_BEJEGYZES (BEJEGYZES_ID,CSOPORT_ID) values ('44','3');
Insert into CSOPORT_BEJEGYZES (BEJEGYZES_ID,CSOPORT_ID) values ('45','3');
Insert into CSOPORT_BEJEGYZES (BEJEGYZES_ID,CSOPORT_ID) values ('46','4');
Insert into CSOPORT_BEJEGYZES (BEJEGYZES_ID,CSOPORT_ID) values ('50','5');
Insert into CSOPORT_BEJEGYZES (BEJEGYZES_ID,CSOPORT_ID) values ('52','4');
Insert into CSOPORT_BEJEGYZES (BEJEGYZES_ID,CSOPORT_ID) values ('56','1');
Insert into CSOPORT_BEJEGYZES (BEJEGYZES_ID,CSOPORT_ID) values ('57','3');
Insert into CSOPORT_BEJEGYZES (BEJEGYZES_ID,CSOPORT_ID) values ('64','7');
Insert into CSOPORT_BEJEGYZES (BEJEGYZES_ID,CSOPORT_ID) values ('65','6');
Insert into CSOPORT_BEJEGYZES (BEJEGYZES_ID,CSOPORT_ID) values ('66','2');
REM INSERTING into CSOPORTOT_KEZEL
SET DEFINE OFF;
Insert into CSOPORTOT_KEZEL (FELHASZNALONEV,CSOPORT_ID) values ('gamergirl##','2');
Insert into CSOPORTOT_KEZEL (FELHASZNALONEV,CSOPORT_ID) values ('gamergirl##','6');
Insert into CSOPORTOT_KEZEL (FELHASZNALONEV,CSOPORT_ID) values ('gyilkos_mester','1');
Insert into CSOPORTOT_KEZEL (FELHASZNALONEV,CSOPORT_ID) values ('gyilkos_mester','3');
Insert into CSOPORTOT_KEZEL (FELHASZNALONEV,CSOPORT_ID) values ('kovacska','3');
Insert into CSOPORTOT_KEZEL (FELHASZNALONEV,CSOPORT_ID) values ('kovacska','4');
Insert into CSOPORTOT_KEZEL (FELHASZNALONEV,CSOPORT_ID) values ('kovacska','7');
Insert into CSOPORTOT_KEZEL (FELHASZNALONEV,CSOPORT_ID) values ('lakatos','1');
Insert into CSOPORTOT_KEZEL (FELHASZNALONEV,CSOPORT_ID) values ('petya12','5');
Insert into CSOPORTOT_KEZEL (FELHASZNALONEV,CSOPORT_ID) values ('varga_aura','7');
Insert into CSOPORTOT_KEZEL (FELHASZNALONEV,CSOPORT_ID) values ('wolfkiller','2');
REM INSERTING into CSOPORT_TAGJA
SET DEFINE OFF;
Insert into CSOPORT_TAGJA (CSOPORT_ID,FELHASZNALONEV) values ('1','julcsi');
Insert into CSOPORT_TAGJA (CSOPORT_ID,FELHASZNALONEV) values ('1','ninjasniper');
Insert into CSOPORT_TAGJA (CSOPORT_ID,FELHASZNALONEV) values ('1','petya12');
Insert into CSOPORT_TAGJA (CSOPORT_ID,FELHASZNALONEV) values ('2','gyilkos_mester');
Insert into CSOPORT_TAGJA (CSOPORT_ID,FELHASZNALONEV) values ('2','julcsi');
Insert into CSOPORT_TAGJA (CSOPORT_ID,FELHASZNALONEV) values ('2','petya12');
Insert into CSOPORT_TAGJA (CSOPORT_ID,FELHASZNALONEV) values ('3','wolfkiller');
Insert into CSOPORT_TAGJA (CSOPORT_ID,FELHASZNALONEV) values ('4','julcsi');
Insert into CSOPORT_TAGJA (CSOPORT_ID,FELHASZNALONEV) values ('5','julcsi');
Insert into CSOPORT_TAGJA (CSOPORT_ID,FELHASZNALONEV) values ('6','julcsi');
Insert into CSOPORT_TAGJA (CSOPORT_ID,FELHASZNALONEV) values ('6','varga_aura');
Insert into CSOPORT_TAGJA (CSOPORT_ID,FELHASZNALONEV) values ('7','julcsi');
Insert into CSOPORT_TAGJA (CSOPORT_ID,FELHASZNALONEV) values ('7','varga_aura');
REM INSERTING into FELHASZNALO
SET DEFINE OFF;
Insert into FELHASZNALO (FELHASZNALONEV,NEV,JELSZO,EMAIL,REGISZTRACIO_DATUM,NEVNAP,ALLAPOT,SZULINAP,ISMEROSOK_SZAMA) values ('petya12','Kiss Péter','$2y$10$KIZ4oOr.1JXS/VGgJnqA6eTWqRN3yvmD36Mi8bJ1URRBA57hEUyb.','peter.kiss@example.com',to_date('25-MÁRC. -23','RR-MON-DD'),'03.15.','Inaktív',to_date('95-MÁJ.  -21','RR-MON-DD'),'10');
Insert into FELHASZNALO (FELHASZNALONEV,NEV,JELSZO,EMAIL,REGISZTRACIO_DATUM,NEVNAP,ALLAPOT,SZULINAP,ISMEROSOK_SZAMA) values ('gamergirl##','Nagy Anna','$2y$10$KIZ4oOr.1JXS/VGgJnqA6eTWqRN3yvmD36Mi8bJ1URRBA57hEUyb.','nagy.anna@example.com',to_date('25-MÁRC. -23','RR-MON-DD'),'06.18.','Inaktív',to_date('98-SZEPT.-12','RR-MON-DD'),'5');
Insert into FELHASZNALO (FELHASZNALONEV,NEV,JELSZO,EMAIL,REGISZTRACIO_DATUM,NEVNAP,ALLAPOT,SZULINAP,ISMEROSOK_SZAMA) values ('tothgabi','Tóth Gábor','$2y$10$KIZ4oOr.1JXS/VGgJnqA6eTWqRN3yvmD36Mi8bJ1URRBA57hEUyb.','toth.gabor@example.com',to_date('25-MÁRC. -23','RR-MON-DD'),'01.30.','Inaktív',to_date('00-NOV.  -05','RR-MON-DD'),'8');
Insert into FELHASZNALO (FELHASZNALONEV,NEV,JELSZO,EMAIL,REGISZTRACIO_DATUM,NEVNAP,ALLAPOT,SZULINAP,ISMEROSOK_SZAMA) values ('szabeszka12','Szabó Erika','$2y$10$KIZ4oOr.1JXS/VGgJnqA6eTWqRN3yvmD36Mi8bJ1URRBA57hEUyb.','szabo.erika@example.com',to_date('25-MÁRC. -23','RR-MON-DD'),'04.22.','Inaktív',to_date('92-JÚL.  -07','RR-MON-DD'),'12');
Insert into FELHASZNALO (FELHASZNALONEV,NEV,JELSZO,EMAIL,REGISZTRACIO_DATUM,NEVNAP,ALLAPOT,SZULINAP,ISMEROSOK_SZAMA) values ('ninjasniper','Horváth László','$2y$10$KIZ4oOr.1JXS/VGgJnqA6eTWqRN3yvmD36Mi8bJ1URRBA57hEUyb.','horvath.laszlo@example.com',to_date('25-MÁRC. -23','RR-MON-DD'),'08.10.','Inaktív',to_date('87-DEC.  -25','RR-MON-DD'),'4');
Insert into FELHASZNALO (FELHASZNALONEV,NEV,JELSZO,EMAIL,REGISZTRACIO_DATUM,NEVNAP,ALLAPOT,SZULINAP,ISMEROSOK_SZAMA) values ('kovacska','Kovács István','$2y$10$KIZ4oOr.1JXS/VGgJnqA6eTWqRN3yvmD36Mi8bJ1URRBA57hEUyb.','kovacs.istvan@example.com',to_date('25-MÁRC. -23','RR-MON-DD'),'09.13.','Inaktív',to_date('90-JAN.  -25','RR-MON-DD'),'20');
Insert into FELHASZNALO (FELHASZNALONEV,NEV,JELSZO,EMAIL,REGISZTRACIO_DATUM,NEVNAP,ALLAPOT,SZULINAP,ISMEROSOK_SZAMA) values ('varga_aura','Varga Laura','$2y$10$KIZ4oOr.1JXS/VGgJnqA6eTWqRN3yvmD36Mi8bJ1URRBA57hEUyb.','varga.laura@example.com',to_date('25-MÁRC. -23','RR-MON-DD'),'02.28.','Inaktív',to_date('93-JÚL.  -17','RR-MON-DD'),'15');
Insert into FELHASZNALO (FELHASZNALONEV,NEV,JELSZO,EMAIL,REGISZTRACIO_DATUM,NEVNAP,ALLAPOT,SZULINAP,ISMEROSOK_SZAMA) values ('wolfkiller','Farkas Zoltán','$2y$10$KIZ4oOr.1JXS/VGgJnqA6eTWqRN3yvmD36Mi8bJ1URRBA57hEUyb.','farkas.zoltan@example.com',to_date('25-MÁRC. -23','RR-MON-DD'),'05.09.','Inaktív',to_date('97-NOV.  -11','RR-MON-DD'),'25');
Insert into FELHASZNALO (FELHASZNALONEV,NEV,JELSZO,EMAIL,REGISZTRACIO_DATUM,NEVNAP,ALLAPOT,SZULINAP,ISMEROSOK_SZAMA) values ('julcsi','Tóth Júlia','$2y$10$KIZ4oOr.1JXS/VGgJnqA6eTWqRN3yvmD36Mi8bJ1URRBA57hEUyb.','toth.julia@example.com',to_date('25-MÁRC. -23','RR-MON-DD'),'07.20.','Inaktív',to_date('92-MÁRC. -03','RR-MON-DD'),'10');
Insert into FELHASZNALO (FELHASZNALONEV,NEV,JELSZO,EMAIL,REGISZTRACIO_DATUM,NEVNAP,ALLAPOT,SZULINAP,ISMEROSOK_SZAMA) values ('szenteltviz','Papp Gergely','$2y$10$KIZ4oOr.1JXS/VGgJnqA6eTWqRN3yvmD36Mi8bJ1URRBA57hEUyb.','papp.gergely@example.com',to_date('25-MÁRC. -23','RR-MON-DD'),'10.10.','Inaktív',to_date('91-FEBR. -15','RR-MON-DD'),'18');
Insert into FELHASZNALO (FELHASZNALONEV,NEV,JELSZO,EMAIL,REGISZTRACIO_DATUM,NEVNAP,ALLAPOT,SZULINAP,ISMEROSOK_SZAMA) values ('germanklau','Németh Klaudia','$2y$10$KIZ4oOr.1JXS/VGgJnqA6eTWqRN3yvmD36Mi8bJ1URRBA57hEUyb.','nemeth.klaudia@example.com',to_date('25-MÁRC. -23','RR-MON-DD'),'11.21.','Inaktív',to_date('95-MÁJ.  -30','RR-MON-DD'),'22');
Insert into FELHASZNALO (FELHASZNALONEV,NEV,JELSZO,EMAIL,REGISZTRACIO_DATUM,NEVNAP,ALLAPOT,SZULINAP,ISMEROSOK_SZAMA) values ('bélabá','Horváth Béla','$2y$10$KIZ4oOr.1JXS/VGgJnqA6eTWqRN3yvmD36Mi8bJ1URRBA57hEUyb.','horvath.bela@example.com',to_date('25-MÁRC. -23','RR-MON-DD'),'12.12.','Inaktív',to_date('90-AUG.  -19','RR-MON-DD'),'8');
Insert into FELHASZNALO (FELHASZNALONEV,NEV,JELSZO,EMAIL,REGISZTRACIO_DATUM,NEVNAP,ALLAPOT,SZULINAP,ISMEROSOK_SZAMA) values ('nyolca','Kiss Petra','$2y$10$KIZ4oOr.1JXS/VGgJnqA6eTWqRN3yvmD36Mi8bJ1URRBA57hEUyb.','kiss.petra@example.com',to_date('25-MÁRC. -23','RR-MON-DD'),'03.05.','Inaktív',to_date('98-DEC.  -30','RR-MON-DD'),'30');
Insert into FELHASZNALO (FELHASZNALONEV,NEV,JELSZO,EMAIL,REGISZTRACIO_DATUM,NEVNAP,ALLAPOT,SZULINAP,ISMEROSOK_SZAMA) values ('lakatos','Lukács Márk','$2y$10$KIZ4oOr.1JXS/VGgJnqA6eTWqRN3yvmD36Mi8bJ1URRBA57hEUyb.','lukacs.mark@example.com',to_date('25-MÁRC. -23','RR-MON-DD'),'04.04.','Inaktív',to_date('96-JÚN.  -22','RR-MON-DD'),'12');
Insert into FELHASZNALO (FELHASZNALONEV,NEV,JELSZO,EMAIL,REGISZTRACIO_DATUM,NEVNAP,ALLAPOT,SZULINAP,ISMEROSOK_SZAMA) values ('gyilkos_mester','Mészáros Ádám','$2y$10$KIZ4oOr.1JXS/VGgJnqA6eTWqRN3yvmD36Mi8bJ1URRBA57hEUyb.','meszaros.adam@example.com',to_date('25-MÁRC. -23','RR-MON-DD'),'01.15.','Inaktív',to_date('94-OKT.  -10','RR-MON-DD'),'35');
Insert into FELHASZNALO (FELHASZNALONEV,NEV,JELSZO,EMAIL,REGISZTRACIO_DATUM,NEVNAP,ALLAPOT,SZULINAP,ISMEROSOK_SZAMA) values ('csunyauser','Kalmar adam','$2y$10$ghHvBF1FItCjchMnJ/yOKOMuvi2XSN0R2XZshNWtqQYosWswb1lle','csunya@example.com',to_date('25-MÁJ.  -01','RR-MON-DD'),'12.12.','Inaktív',to_date('87-DEC.  -12','RR-MON-DD'),'0');
REM INSERTING into ISMEROS_KERELEM
SET DEFINE OFF;
Insert into ISMEROS_KERELEM (ISMEROS_KERELEM_ID,ALLAPOT,LETREHOZAS_DATUMA,FOGADO,FELHASZNALONEV) values ('7','Elfogadva',to_date('25-ÁPR.  -25','RR-MON-DD'),'julcsi','petya12');
Insert into ISMEROS_KERELEM (ISMEROS_KERELEM_ID,ALLAPOT,LETREHOZAS_DATUMA,FOGADO,FELHASZNALONEV) values ('1','Elküldve',to_date('25-MÁRC. -23','RR-MON-DD'),'tothgabi','petya12');
Insert into ISMEROS_KERELEM (ISMEROS_KERELEM_ID,ALLAPOT,LETREHOZAS_DATUMA,FOGADO,FELHASZNALONEV) values ('2','Elküldve',to_date('25-MÁRC. -23','RR-MON-DD'),'julcsi','tothgabi');
Insert into ISMEROS_KERELEM (ISMEROS_KERELEM_ID,ALLAPOT,LETREHOZAS_DATUMA,FOGADO,FELHASZNALONEV) values ('3','Elküldve',to_date('25-MÁRC. -23','RR-MON-DD'),'kovacska','ninjasniper');
Insert into ISMEROS_KERELEM (ISMEROS_KERELEM_ID,ALLAPOT,LETREHOZAS_DATUMA,FOGADO,FELHASZNALONEV) values ('4','Elfogadva',to_date('25-MÁRC. -23','RR-MON-DD'),'petya12','germanklau');
Insert into ISMEROS_KERELEM (ISMEROS_KERELEM_ID,ALLAPOT,LETREHOZAS_DATUMA,FOGADO,FELHASZNALONEV) values ('5','Elküldve',to_date('25-MÁRC. -23','RR-MON-DD'),'ninjasniper','gyilkos_mester');
Insert into ISMEROS_KERELEM (ISMEROS_KERELEM_ID,ALLAPOT,LETREHOZAS_DATUMA,FOGADO,FELHASZNALONEV) values ('6','Elfogadva',to_date('25-MÁRC. -20','RR-MON-DD'),'kovacska','germanklau');
Insert into ISMEROS_KERELEM (ISMEROS_KERELEM_ID,ALLAPOT,LETREHOZAS_DATUMA,FOGADO,FELHASZNALONEV) values ('8','Elfogadva',to_date('25-ÁPR.  -26','RR-MON-DD'),'szabeszka12','petya12');
REM INSERTING into JELENTES
SET DEFINE OFF;
REM INSERTING into KEPEK
SET DEFINE OFF;
REM INSERTING into KOMMENT
SET DEFINE OFF;
Insert into KOMMENT (LETREHOZAS_DATUMA,KOMMENT,FELHASZNALONEV,BEJEGYZES_ID,KOMMENT_ID) values (to_date('25-ÁPR.  -26','RR-MON-DD'),'nem rossz szándékból','julcsi','4','36');
Insert into KOMMENT (LETREHOZAS_DATUMA,KOMMENT,FELHASZNALONEV,BEJEGYZES_ID,KOMMENT_ID) values (to_date('25-ÁPR.  -26','RR-MON-DD'),'rendben van
','nyolca','1','37');
Insert into KOMMENT (LETREHOZAS_DATUMA,KOMMENT,FELHASZNALONEV,BEJEGYZES_ID,KOMMENT_ID) values (to_date('25-ÁPR.  -26','RR-MON-DD'),'nekem annyira nem tetszik','julcsi','2','35');
Insert into KOMMENT (LETREHOZAS_DATUMA,KOMMENT,FELHASZNALONEV,BEJEGYZES_ID,KOMMENT_ID) values (to_date('25-ÁPR.  -26','RR-MON-DD'),'azt en is szeretem','varga_aura','2','34');
Insert into KOMMENT (LETREHOZAS_DATUMA,KOMMENT,FELHASZNALONEV,BEJEGYZES_ID,KOMMENT_ID) values (to_date('25-MÁJ.  -01','RR-MON-DD'),'szia','tothgabi','43','38');
Insert into KOMMENT (LETREHOZAS_DATUMA,KOMMENT,FELHASZNALONEV,BEJEGYZES_ID,KOMMENT_ID) values (to_date('25-MÁJ.  -01','RR-MON-DD'),'az gatya','petya12','43','39');
REM INSERTING into LAJKOLAS
SET DEFINE OFF;
Insert into LAJKOLAS (FELHASZNALONEV,BEJEGYZES_ID) values ('julcsi','1');
Insert into LAJKOLAS (FELHASZNALONEV,BEJEGYZES_ID) values ('nyolca','1');
Insert into LAJKOLAS (FELHASZNALONEV,BEJEGYZES_ID) values ('julcsi','2');
Insert into LAJKOLAS (FELHASZNALONEV,BEJEGYZES_ID) values ('nyolca','2');
Insert into LAJKOLAS (FELHASZNALONEV,BEJEGYZES_ID) values ('nyolca','4');
Insert into LAJKOLAS (FELHASZNALONEV,BEJEGYZES_ID) values ('julcsi','43');
Insert into LAJKOLAS (FELHASZNALONEV,BEJEGYZES_ID) values ('petya12','43');
Insert into LAJKOLAS (FELHASZNALONEV,BEJEGYZES_ID) values ('tothgabi','43');
REM INSERTING into PRIVAT_UZENET
SET DEFINE OFF;
Insert into PRIVAT_UZENET (UZENET_ID,SZOVEG,CIMZETT,KULDES_DATUMA,FELHASZNALONEV) values ('1','Szia, hogy vagy?','gamergirl##',to_date('25-MÁRC. -23','RR-MON-DD'),'gyilkos_mester');
Insert into PRIVAT_UZENET (UZENET_ID,SZOVEG,CIMZETT,KULDES_DATUMA,FELHASZNALONEV) values ('6','Nagyon hiányzól','gamergirl##',to_date('25-MÁRC. -23','RR-MON-DD'),'gyilkos_mester');
Insert into PRIVAT_UZENET (UZENET_ID,SZOVEG,CIMZETT,KULDES_DATUMA,FELHASZNALONEV) values ('7','Még mindig szeretlek','gamergirl##',to_date('25-MÁRC. -23','RR-MON-DD'),'gyilkos_mester');
Insert into PRIVAT_UZENET (UZENET_ID,SZOVEG,CIMZETT,KULDES_DATUMA,FELHASZNALONEV) values ('2','Mikor találkozunk?','tothgabi',to_date('25-MÁRC. -23','RR-MON-DD'),'tothgabi');
Insert into PRIVAT_UZENET (UZENET_ID,SZOVEG,CIMZETT,KULDES_DATUMA,FELHASZNALONEV) values ('3','Küldök egy receptet!','szabeszka12',to_date('25-MÁRC. -23','RR-MON-DD'),'petya12');
Insert into PRIVAT_UZENET (UZENET_ID,SZOVEG,CIMZETT,KULDES_DATUMA,FELHASZNALONEV) values ('4','Megvan a festmény!','bélabá',to_date('25-MÁRC. -23','RR-MON-DD'),'ninjasniper');
Insert into PRIVAT_UZENET (UZENET_ID,SZOVEG,CIMZETT,KULDES_DATUMA,FELHASZNALONEV) values ('5','Gratulálok a futáshoz!','petya12',to_date('25-MÁRC. -23','RR-MON-DD'),'kovacska');
Insert into PRIVAT_UZENET (UZENET_ID,SZOVEG,CIMZETT,KULDES_DATUMA,FELHASZNALONEV) values ('8','hogy van bélabá?','petya12',to_date('25-MÁJ.  -07','RR-MON-DD'),'bélabá');
--------------------------------------------------------
--  DDL for Index CSOPORTOT_KEZEL_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "CSOPORTOT_KEZEL_PK" ON "CSOPORTOT_KEZEL" ("FELHASZNALONEV", "CSOPORT_ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index CSOPORT_BEJEGYZES_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "CSOPORT_BEJEGYZES_PK" ON "CSOPORT_BEJEGYZES" ("BEJEGYZES_ID", "CSOPORT_ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index CSOPORT_TAGJA_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "CSOPORT_TAGJA_PK" ON "CSOPORT_TAGJA" ("CSOPORT_ID", "FELHASZNALONEV") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index LIKEOLAS_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "LIKEOLAS_PK" ON "LAJKOLAS" ("BEJEGYZES_ID", "FELHASZNALONEV") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index KOMMENT_PK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "KOMMENT_PK1" ON "KOMMENT" ("KOMMENT_ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Trigger ADMIN_ALLAPOT_FRISSITES
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "ADMIN_ALLAPOT_FRISSITES" 
AFTER UPDATE OF allapot ON felhasznalo
FOR EACH ROW
BEGIN
  IF :OLD.allapot != :NEW.allapot THEN
    UPDATE admin
    SET allapot = :NEW.allapot
    WHERE adminnev = :NEW.felhasznalonev;
  END IF;
END;



/
ALTER TRIGGER "ADMIN_ALLAPOT_FRISSITES" ENABLE;
--------------------------------------------------------
--  DDL for Trigger CHECK_BEJEGYZES_LEIRAS_HOSSZ
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "CHECK_BEJEGYZES_LEIRAS_HOSSZ" 
BEFORE INSERT OR UPDATE ON BEJEGYZES
FOR EACH ROW
BEGIN
    IF LENGTH(:NEW.LEIRAS) > 255 THEN
        RAISE_APPLICATION_ERROR(-20001, 'A bejegyzés leírása nem lehet hosszabb 255 karakternél.');
    END IF;
END;



/
ALTER TRIGGER "CHECK_BEJEGYZES_LEIRAS_HOSSZ" ENABLE;
--------------------------------------------------------
--  DDL for Trigger ELSO_ISMERETSEG
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "ELSO_ISMERETSEG" 
AFTER UPDATE OF ALLAPOT ON ISMEROS_KERELEM
FOR EACH ROW
  WHEN (NEW.ALLAPOT = 'Elfogadva' AND OLD.ALLAPOT != 'Elfogadva') DECLARE
    v_ism_count NUMBER;
BEGIN
    -- Ellenőrizzük, hogy hány ismerőse van a fogadó felhasználónak
    SELECT ISMEROSOK_SZAMA INTO v_ism_count 
    FROM FELHASZNALO 
    WHERE FELHASZNALONEV = :NEW.FOGADO;

    -- Ha ez az első ismeretség
    IF v_ism_count = 1 THEN
        -- Rendszerüzenet küldése
        INSERT INTO PRIVAT_UZENET (
            UZENET_ID, 
            SZOVEG, 
            CIMZETT, 
            KULDES_DATUMA, 
            FELHASZNALONEV
        ) VALUES (
            (SELECT NVL(MAX(UZENET_ID), 0) + 1 FROM PRIVAT_UZENET),
            'Gratulálunk az első ismeretséghez! Kezdj el új barátokat keresni és bővítsd a kapcsolati hálódat!',
            :NEW.FOGADO,
            SYSDATE,
            'Rendszer'
        );
    END IF;
END;

/
ALTER TRIGGER "ELSO_ISMERETSEG" ENABLE;
--------------------------------------------------------
--  DDL for Trigger EZREDIK_ISMERETSEG
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "EZREDIK_ISMERETSEG" 
AFTER UPDATE OF ALLAPOT ON ISMEROS_KERELEM
FOR EACH ROW
  WHEN (NEW.ALLAPOT = 'Elfogadva' AND OLD.ALLAPOT != 'Elfogadva') DECLARE
    v_ism_count NUMBER;
BEGIN
    -- Ellenőrizzük, hogy hány ismerőse van a fogadó felhasználónak
    SELECT ISMEROSOK_SZAMA INTO v_ism_count 
    FROM FELHASZNALO 
    WHERE FELHASZNALONEV = :NEW.FOGADO;

    -- Ha ez az ezredik ismeretség
    IF v_ism_count = 1000 THEN
        -- Rendszerüzenet küldése
        INSERT INTO PRIVAT_UZENET (
            UZENET_ID, 
            SZOVEG, 
            CIMZETT, 
            KULDES_DATUMA, 
            FELHASZNALONEV
        ) VALUES (
            (SELECT NVL(MAX(UZENET_ID), 0) + 1 FROM PRIVAT_UZENET),
            'Elképesztő! Elérted az 1000 ismeretséget! Te vagy a közösség sztárja!',
            :NEW.FOGADO,
            SYSDATE,
            'Rendszer'
        );
    END IF;
END;

/
ALTER TRIGGER "EZREDIK_ISMERETSEG" ENABLE;
--------------------------------------------------------
--  DDL for Trigger FELHASZNALONEV_ELLENORZES
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "FELHASZNALONEV_ELLENORZES" 
BEFORE INSERT OR UPDATE ON FELHASZNALO
FOR EACH ROW
DECLARE
    tragar_szavak SYS.ODCIVARCHAR2LIST := SYS.ODCIVARCHAR2LIST('csunya1', 'csunya2', 'csunya3'); -- Trágár szavak listája
    szo VARCHAR2(100);
BEGIN
    -- Ellenőrizzük, hogy a felhasználónév tartalmaz-e trágár szót
    FOR i IN 1..tragar_szavak.COUNT LOOP
        szo := tragar_szavak(i);
        IF INSTR(LOWER(:NEW.FELHASZNALONEV), LOWER(szo)) > 0 THEN
            RAISE_APPLICATION_ERROR(-20001, 'A felhasználónév trágár szót tartalmaz: ' || szo);
        END IF;
    END LOOP;
END;



/
ALTER TRIGGER "FELHASZNALONEV_ELLENORZES" ENABLE;
--------------------------------------------------------
--  DDL for Trigger ISMEROSOK_SZAMA_FRISSITES
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "ISMEROSOK_SZAMA_FRISSITES" 
AFTER UPDATE OF ALLAPOT ON ISMEROS_KERELEM
FOR EACH ROW
  WHEN (NEW.ALLAPOT = 'Elfogadva' AND OLD.ALLAPOT != 'Elfogadva') BEGIN
    -- Frissítjük a fogadó ismerőseinek számát
    UPDATE FELHASZNALO
    SET ISMEROSOK_SZAMA = ISMEROSOK_SZAMA + 1
    WHERE FELHASZNALONEV = :NEW.FOGADO;

    -- Frissítjük a küldő ismerőseinek számát
    UPDATE FELHASZNALO
    SET ISMEROSOK_SZAMA = ISMEROSOK_SZAMA + 1
    WHERE FELHASZNALONEV = :NEW.FELHASZNALONEV;
END;

/
ALTER TRIGGER "ISMEROSOK_SZAMA_FRISSITES" ENABLE;
--------------------------------------------------------
--  DDL for Trigger SZAZADIK_ISMERETSEG
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "SZAZADIK_ISMERETSEG" 
AFTER UPDATE OF ALLAPOT ON ISMEROS_KERELEM
FOR EACH ROW
  WHEN (NEW.ALLAPOT = 'Elfogadva' AND OLD.ALLAPOT != 'Elfogadva') DECLARE
    v_ism_count NUMBER;
BEGIN
    -- Ellenőrizzük, hogy hány ismerőse van a fogadó felhasználónak
    SELECT ISMEROSOK_SZAMA INTO v_ism_count 
    FROM FELHASZNALO 
    WHERE FELHASZNALONEV = :NEW.FOGADO;

    -- Ha ez a századik ismeretség
    IF v_ism_count = 100 THEN
        -- Rendszerüzenet küldése
        INSERT INTO PRIVAT_UZENET (
            UZENET_ID, 
            SZOVEG, 
            CIMZETT, 
            KULDES_DATUMA, 
            FELHASZNALONEV
        ) VALUES (
            (SELECT NVL(MAX(UZENET_ID), 0) + 1 FROM PRIVAT_UZENET),
            'Gratulálunk a századik ismeretséghez! Te vagy a közösség egyik legnépszerűbb tagja!',
            :NEW.FOGADO,
            SYSDATE,
            'Rendszer'
        );
    END IF;
END;

/
ALTER TRIGGER "SZAZADIK_ISMERETSEG" ENABLE;
--------------------------------------------------------
--  DDL for Trigger UPDATE_POST_TIMESTAMP
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE TRIGGER "UPDATE_POST_TIMESTAMP" 
BEFORE UPDATE ON BEJEGYZES
FOR EACH ROW
BEGIN
    :NEW.FELTOLTES_IDEJE := SYSDATE;
END;



/
ALTER TRIGGER "UPDATE_POST_TIMESTAMP" ENABLE;
--------------------------------------------------------
--  DDL for Procedure ALLAPOT_FRISSITES
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "ALLAPOT_FRISSITES" (p_felhasznalonev IN VARCHAR2) IS
BEGIN
  UPDATE felhasznalo
  SET allapot = CASE
                 WHEN allapot = 'Aktív' THEN 'Inaktív'
                 ELSE 'Aktív'
               END
  WHERE felhasznalonev = p_felhasznalonev;
  COMMIT;
END;



/
--------------------------------------------------------
--  DDL for Procedure BEJEGYZES_HOZZAADAS
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "BEJEGYZES_HOZZAADAS" (
    p_LEIRAS in VARCHAR2,
    P_SZOVEG IN VARCHAR2,
    P_FELHASZNALONEV IN VARCHAR2,
    P_BEJEGYZES_ID OUT NUMBER
) AS
BEGIN
    INSERT INTO BEJEGYZES (BEJEGYZES_ID,LEIRAS ,FELTOLTES_IDEJE,SZOVEG, FELHASZNALONEV)
    VALUES (BEJEGYZES_ID_SEQ.NEXTVAL, P_LEIRAS,  SYSDATE,P_SZOVEG ,P_FELHASZNALONEV)
    RETURNING BEJEGYZES_ID INTO P_BEJEGYZES_ID;
END;



/
--------------------------------------------------------
--  DDL for Procedure CSOPORTBA_CSATLAKOZAS
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "CSOPORTBA_CSATLAKOZAS" (
    p_csoport_id IN NUMBER,
    p_felhasznalonev IN VARCHAR2
) AS
    v_count NUMBER;
BEGIN
        -- Insert into group if not already a member
        INSERT INTO CSOPORT_TAGJA (
            CSOPORT_ID, 
            FELHASZNALONEV
        ) VALUES (
            p_csoport_id, 
            p_felhasznalonev
        );   
        COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
        RAISE;
END CSOPORTBA_CSATLAKOZAS;


/
--------------------------------------------------------
--  DDL for Procedure HOZZASZOLAS_HOZZAADAS
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "HOZZASZOLAS_HOZZAADAS" (
    p_bejegyzes_id IN NUMBER,
    p_felhasznalonev IN VARCHAR2,
    p_komment_szoveg IN VARCHAR2
) AS
BEGIN
    INSERT INTO KOMMENT (KOMMENT_ID, BEJEGYZES_ID, KOMMENT, LETREHOZAS_DATUMA, FELHASZNALONEV)
    VALUES (KOMMENT_SEQ.NEXTVAL, p_bejegyzes_id, p_komment_szoveg, SYSDATE, p_felhasznalonev);

    COMMIT;
END HOZZASZOLAS_HOZZAADAS;



/
--------------------------------------------------------
--  DDL for Procedure JELOLES_HOZZAADAS
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "JELOLES_HOZZAADAS" (
    p_id IN NUMBER,
    p_fogado IN VARCHAR2,
    p_felhasznalonev IN VARCHAR2
) AS
BEGIN
    INSERT INTO ISMEROS_KERELEM (
        ISMEROS_KERELEM_ID, 
        ALLAPOT, 
        LETREHOZAS_DATUMA, 
        FOGADO, 
        FELHASZNALONEV
    ) VALUES (
        p_id, 
        'SEND', 
        SYSDATE, 
        p_fogado, 
        p_felhasznalonev
    );

    COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
        RAISE;
END JELOLES_HOZZAADAS;


/
--------------------------------------------------------
--  DDL for Procedure SZEMELY_KKIR
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SZEMELY_KKIR" (p_szigsz IN Szemelyek.szigsz%TYPE)
IS
    -- Lokális deklaráció
    v_szemely Szemelyek%ROWTYPE;
BEGIN
    SELECT * INTO v_szemely FROM Szemelyek WHERE szigsz = p_szigsz;
    DBMS_OUTPUT.PUT_LINE('A személy személyigazolványszáma: ' ||
    v_szemely.szigsz || ', neve: ' || v_szemely.nev || ', születési dátuma ' ||
    TO_CHAR(v_szemely.szuletesiDatum,'YYYY.MM.DD') || ', irányítószáma: ' ||
    v_szemely.irsz || ', országa: ' || v_szemely.orszag ||
    ', hallgatói státusza: ' || v_szemely.hallgatoE || ' .');
END;

-- Külön PL/SQL blokk
SET SERVEROUTPUT ON
BEGIN
    -- Eljárás hívása
    szemely_kkir('840388SD');
END;


/
--------------------------------------------------------
--  DDL for Procedure UZENET_MENTESE
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "UZENET_MENTESE" (
    p_id IN PRIVAT_UZENET.UZENET_ID%TYPE,
    p_szoveg IN PRIVAT_UZENET.SZOVEG%TYPE,
    p_cimzett IN PRIVAT_UZENET.CIMZETT%TYPE,
    p_felhasznalonev IN PRIVAT_UZENET.FELHASZNALONEV%TYPE
)
IS
BEGIN
    INSERT INTO PRIVAT_UZENET (UZENET_ID, SZOVEG, CIMZETT, KULDES_DATUMA, FELHASZNALONEV) 
    VALUES (p_id, p_szoveg, p_cimzett, SYSDATE, p_felhasznalonev);

    COMMIT;

    DBMS_OUTPUT.PUT_LINE('Üzenet sikeresen mentve. ID: ' || p_id);
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
        DBMS_OUTPUT.PUT_LINE('Hiba történt az üzenet mentésekor: ' || SQLERRM);
        RAISE;
END UZENET_MENTESE;


/
--------------------------------------------------------
--  DDL for Function CSOPORTBA_VANE
--------------------------------------------------------

  CREATE OR REPLACE EDITIONABLE FUNCTION "CSOPORTBA_VANE" (
    p_csoport_id IN NUMBER,
    p_felhasznalonev IN VARCHAR2
) RETURN NUMBER AS
    v_count NUMBER;
BEGIN
    SELECT COUNT(*) 
    INTO v_count
    FROM CSOPORT_TAGJA 
    WHERE FELHASZNALONEV = p_felhasznalonev 
    AND CSOPORT_ID = p_csoport_id;

    RETURN v_count;
EXCEPTION
    WHEN OTHERS THEN
        -- Return -1 to indicate an error occurred
        RETURN -1;
END CSOPORTBA_VANE;


/
--------------------------------------------------------
--  Constraints for Table JELENTES
--------------------------------------------------------

  ALTER TABLE "JELENTES" MODIFY ("TIPUS" NOT NULL ENABLE);
  ALTER TABLE "JELENTES" MODIFY ("JELENTETT" NOT NULL ENABLE);
  ALTER TABLE "JELENTES" MODIFY ("STATUSZ" NOT NULL ENABLE);
  ALTER TABLE "JELENTES" MODIFY ("LEIRAS" NOT NULL ENABLE);
  ALTER TABLE "JELENTES" MODIFY ("ADMINNEV" NOT NULL ENABLE);
  ALTER TABLE "JELENTES" MODIFY ("FELHASZNALONEV" NOT NULL ENABLE);
  ALTER TABLE "JELENTES" ADD PRIMARY KEY ("JELENTES_ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
--------------------------------------------------------
--  Constraints for Table CSOPORT_TAGJA
--------------------------------------------------------

  ALTER TABLE "CSOPORT_TAGJA" ADD CONSTRAINT "CSOPORT_TAGJA_PK" PRIMARY KEY ("CSOPORT_ID", "FELHASZNALONEV")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
  ALTER TABLE "CSOPORT_TAGJA" MODIFY ("CSOPORT_ID" NOT NULL ENABLE);
  ALTER TABLE "CSOPORT_TAGJA" MODIFY ("FELHASZNALONEV" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table PRIVAT_UZENET
--------------------------------------------------------

  ALTER TABLE "PRIVAT_UZENET" MODIFY ("SZOVEG" NOT NULL ENABLE);
  ALTER TABLE "PRIVAT_UZENET" MODIFY ("CIMZETT" NOT NULL ENABLE);
  ALTER TABLE "PRIVAT_UZENET" MODIFY ("FELHASZNALONEV" NOT NULL ENABLE);
  ALTER TABLE "PRIVAT_UZENET" ADD PRIMARY KEY ("UZENET_ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
--------------------------------------------------------
--  Constraints for Table CSOPORTOT_KEZEL
--------------------------------------------------------

  ALTER TABLE "CSOPORTOT_KEZEL" ADD CONSTRAINT "CSOPORTOT_KEZEL_PK" PRIMARY KEY ("FELHASZNALONEV", "CSOPORT_ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
  ALTER TABLE "CSOPORTOT_KEZEL" MODIFY ("FELHASZNALONEV" NOT NULL ENABLE);
  ALTER TABLE "CSOPORTOT_KEZEL" MODIFY ("CSOPORT_ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table CSOPORT
--------------------------------------------------------

  ALTER TABLE "CSOPORT" MODIFY ("NEV" NOT NULL ENABLE);
  ALTER TABLE "CSOPORT" ADD PRIMARY KEY ("CSOPORT_ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
--------------------------------------------------------
--  Constraints for Table CSOPORT_BEJEGYZES
--------------------------------------------------------

  ALTER TABLE "CSOPORT_BEJEGYZES" ADD CONSTRAINT "CSOPORT_BEJEGYZES_PK" PRIMARY KEY ("BEJEGYZES_ID", "CSOPORT_ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
  ALTER TABLE "CSOPORT_BEJEGYZES" MODIFY ("BEJEGYZES_ID" NOT NULL ENABLE);
  ALTER TABLE "CSOPORT_BEJEGYZES" MODIFY ("CSOPORT_ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table BEJEGYZES
--------------------------------------------------------

  ALTER TABLE "BEJEGYZES" MODIFY ("FELHASZNALONEV" NOT NULL ENABLE);
  ALTER TABLE "BEJEGYZES" ADD PRIMARY KEY ("BEJEGYZES_ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
--------------------------------------------------------
--  Constraints for Table ISMEROS_KERELEM
--------------------------------------------------------

  ALTER TABLE "ISMEROS_KERELEM" MODIFY ("ALLAPOT" NOT NULL ENABLE);
  ALTER TABLE "ISMEROS_KERELEM" MODIFY ("FOGADO" NOT NULL ENABLE);
  ALTER TABLE "ISMEROS_KERELEM" MODIFY ("FELHASZNALONEV" NOT NULL ENABLE);
  ALTER TABLE "ISMEROS_KERELEM" ADD PRIMARY KEY ("ISMEROS_KERELEM_ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
--------------------------------------------------------
--  Constraints for Table KEPEK
--------------------------------------------------------

  ALTER TABLE "KEPEK" MODIFY ("BEJEGYZES_ID" NOT NULL ENABLE);
  ALTER TABLE "KEPEK" ADD PRIMARY KEY ("KEP_ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
  ALTER TABLE "KEPEK" ADD UNIQUE ("BEJEGYZES_ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
--------------------------------------------------------
--  Constraints for Table FELHASZNALO
--------------------------------------------------------

  ALTER TABLE "FELHASZNALO" MODIFY ("NEV" NOT NULL ENABLE);
  ALTER TABLE "FELHASZNALO" MODIFY ("JELSZO" NOT NULL ENABLE);
  ALTER TABLE "FELHASZNALO" MODIFY ("EMAIL" NOT NULL ENABLE);
  ALTER TABLE "FELHASZNALO" MODIFY ("ALLAPOT" NOT NULL ENABLE);
  ALTER TABLE "FELHASZNALO" ADD PRIMARY KEY ("FELHASZNALONEV")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
  ALTER TABLE "FELHASZNALO" ADD UNIQUE ("EMAIL")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
--------------------------------------------------------
--  Constraints for Table LAJKOLAS
--------------------------------------------------------

  ALTER TABLE "LAJKOLAS" ADD CONSTRAINT "LIKEOLAS_PK" PRIMARY KEY ("BEJEGYZES_ID", "FELHASZNALONEV")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
  ALTER TABLE "LAJKOLAS" MODIFY ("FELHASZNALONEV" NOT NULL ENABLE);
  ALTER TABLE "LAJKOLAS" MODIFY ("BEJEGYZES_ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table ADMIN
--------------------------------------------------------

  ALTER TABLE "ADMIN" ADD PRIMARY KEY ("ADMINNEV")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
  ALTER TABLE "ADMIN" MODIFY ("NEV" NOT NULL ENABLE);
  ALTER TABLE "ADMIN" MODIFY ("JELSZO" NOT NULL ENABLE);
  ALTER TABLE "ADMIN" MODIFY ("EMAIL" NOT NULL ENABLE);
  ALTER TABLE "ADMIN" MODIFY ("REGISZTRACIO_DATUM" NOT NULL ENABLE);
  ALTER TABLE "ADMIN" MODIFY ("NEVNAP" NOT NULL ENABLE);
  ALTER TABLE "ADMIN" MODIFY ("ALLAPOT" NOT NULL ENABLE);
  ALTER TABLE "ADMIN" MODIFY ("SZULINAP" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table KOMMENT
--------------------------------------------------------

  ALTER TABLE "KOMMENT" MODIFY ("FELHASZNALONEV" NOT NULL ENABLE);
  ALTER TABLE "KOMMENT" MODIFY ("BEJEGYZES_ID" NOT NULL ENABLE);
  ALTER TABLE "KOMMENT" MODIFY ("KOMMENT_ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Ref Constraints for Table ADMIN
--------------------------------------------------------

  ALTER TABLE "ADMIN" ADD CONSTRAINT "ADMIN_FK1" FOREIGN KEY ("ADMINNEV")
	  REFERENCES "FELHASZNALO" ("FELHASZNALONEV") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table BEJEGYZES
--------------------------------------------------------

  ALTER TABLE "BEJEGYZES" ADD CONSTRAINT "BEJEGYZES_FELHASZNALO" FOREIGN KEY ("FELHASZNALONEV")
	  REFERENCES "FELHASZNALO" ("FELHASZNALONEV") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table CSOPORT_BEJEGYZES
--------------------------------------------------------

  ALTER TABLE "CSOPORT_BEJEGYZES" ADD CONSTRAINT "CSOPORT_BEJEGYZES_FK1" FOREIGN KEY ("CSOPORT_ID")
	  REFERENCES "CSOPORT" ("CSOPORT_ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "CSOPORT_BEJEGYZES" ADD CONSTRAINT "CSOPORT_BEJEGYZES_FK2" FOREIGN KEY ("BEJEGYZES_ID")
	  REFERENCES "BEJEGYZES" ("BEJEGYZES_ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table CSOPORTOT_KEZEL
--------------------------------------------------------

  ALTER TABLE "CSOPORTOT_KEZEL" ADD CONSTRAINT "ADMIN" FOREIGN KEY ("FELHASZNALONEV")
	  REFERENCES "FELHASZNALO" ("FELHASZNALONEV") ON DELETE CASCADE ENABLE;
  ALTER TABLE "CSOPORTOT_KEZEL" ADD CONSTRAINT "CSOPORT_ADMIN" FOREIGN KEY ("CSOPORT_ID")
	  REFERENCES "CSOPORT" ("CSOPORT_ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table CSOPORT_TAGJA
--------------------------------------------------------

  ALTER TABLE "CSOPORT_TAGJA" ADD CONSTRAINT "CSOPORT" FOREIGN KEY ("CSOPORT_ID")
	  REFERENCES "CSOPORT" ("CSOPORT_ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "CSOPORT_TAGJA" ADD CONSTRAINT "FELHASZNALO_CSOPORT" FOREIGN KEY ("FELHASZNALONEV")
	  REFERENCES "FELHASZNALO" ("FELHASZNALONEV") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table ISMEROS_KERELEM
--------------------------------------------------------

  ALTER TABLE "ISMEROS_KERELEM" ADD CONSTRAINT "ISMEROS_KERELEM" FOREIGN KEY ("FELHASZNALONEV")
	  REFERENCES "FELHASZNALO" ("FELHASZNALONEV") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table JELENTES
--------------------------------------------------------

  ALTER TABLE "JELENTES" ADD CONSTRAINT "JELENTES_FK1" FOREIGN KEY ("FELHASZNALONEV")
	  REFERENCES "FELHASZNALO" ("FELHASZNALONEV") ON DELETE CASCADE ENABLE;
  ALTER TABLE "JELENTES" ADD CONSTRAINT "JELENTES_FK2" FOREIGN KEY ("ADMINNEV")
	  REFERENCES "ADMIN" ("ADMINNEV") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table KEPEK
--------------------------------------------------------

  ALTER TABLE "KEPEK" ADD CONSTRAINT "KEPEK" FOREIGN KEY ("BEJEGYZES_ID")
	  REFERENCES "BEJEGYZES" ("BEJEGYZES_ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table KOMMENT
--------------------------------------------------------

  ALTER TABLE "KOMMENT" ADD CONSTRAINT "KOMMENT_FK1" FOREIGN KEY ("BEJEGYZES_ID")
	  REFERENCES "BEJEGYZES" ("BEJEGYZES_ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "KOMMENT" ADD CONSTRAINT "RAGALAS_FELHASZNALO" FOREIGN KEY ("FELHASZNALONEV")
	  REFERENCES "FELHASZNALO" ("FELHASZNALONEV") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table LAJKOLAS
--------------------------------------------------------

  ALTER TABLE "LAJKOLAS" ADD CONSTRAINT "LIKEOLAS_FK1" FOREIGN KEY ("FELHASZNALONEV")
	  REFERENCES "FELHASZNALO" ("FELHASZNALONEV") ON DELETE CASCADE ENABLE;
  ALTER TABLE "LAJKOLAS" ADD CONSTRAINT "LIKEOLAS_FK2" FOREIGN KEY ("BEJEGYZES_ID")
	  REFERENCES "BEJEGYZES" ("BEJEGYZES_ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table PRIVAT_UZENET
--------------------------------------------------------

  ALTER TABLE "PRIVAT_UZENET" ADD CONSTRAINT "PRIVAT_UZENET" FOREIGN KEY ("FELHASZNALONEV")
	  REFERENCES "FELHASZNALO" ("FELHASZNALONEV") ON DELETE CASCADE ENABLE;
