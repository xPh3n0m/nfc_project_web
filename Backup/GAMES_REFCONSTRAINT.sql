--------------------------------------------------------
--  Ref Constraints for Table GAMES
--------------------------------------------------------

  ALTER TABLE "DB2013_G14"."GAMES" ADD FOREIGN KEY ("HOST_COUNTRY")
	  REFERENCES "DB2013_G14"."COUNTRIES" ("NAME") ENABLE;
