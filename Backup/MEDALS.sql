--------------------------------------------------------
--  DDL for Table MEDALS
--------------------------------------------------------

  CREATE TABLE "DB2013_G14"."MEDALS" 
   (	"MEDAL" VARCHAR2(12 BYTE), 
	"OLYMPICS" VARCHAR2(20 BYTE), 
	"AID" NUMBER(*,0), 
	"COUNTRY" VARCHAR2(40 BYTE), 
	"SPORT" VARCHAR2(30 BYTE), 
	"DISCIPLINES" VARCHAR2(60 BYTE)
   ) PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1 BUFFER_POOL DEFAULT)
  TABLESPACE "DB_STUDENTS_2012" ;
