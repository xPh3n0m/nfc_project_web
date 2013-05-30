--------------------------------------------------------
--  Constraints for Table DISCIPLINES
--------------------------------------------------------

  ALTER TABLE "DB2013_G14"."DISCIPLINES" MODIFY ("NAME" NOT NULL ENABLE);
 
  ALTER TABLE "DB2013_G14"."DISCIPLINES" MODIFY ("SPORT" NOT NULL ENABLE);
 
  ALTER TABLE "DB2013_G14"."DISCIPLINES" ADD PRIMARY KEY ("NAME", "SPORT")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1 BUFFER_POOL DEFAULT)
  TABLESPACE "DB_STUDENTS_2012"  ENABLE;
