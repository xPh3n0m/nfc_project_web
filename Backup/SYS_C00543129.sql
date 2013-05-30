--------------------------------------------------------
--  DDL for Index SYS_C00543129
--------------------------------------------------------

  CREATE UNIQUE INDEX "DB2013_G14"."SYS_C00543129" ON "DB2013_G14"."PARTICIPANTS" ("AID", "OLYMPICS", "COUNTRY", "SPORT") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1 BUFFER_POOL DEFAULT)
  TABLESPACE "DB_STUDENTS_2012" ;
