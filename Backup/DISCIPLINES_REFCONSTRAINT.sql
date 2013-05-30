--------------------------------------------------------
--  Ref Constraints for Table DISCIPLINES
--------------------------------------------------------

  ALTER TABLE "DB2013_G14"."DISCIPLINES" ADD FOREIGN KEY ("SPORT")
	  REFERENCES "DB2013_G14"."SPORTS" ("NAME") ON DELETE CASCADE ENABLE;
