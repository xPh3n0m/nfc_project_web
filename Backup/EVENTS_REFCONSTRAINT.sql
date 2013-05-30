--------------------------------------------------------
--  Ref Constraints for Table EVENTS
--------------------------------------------------------

  ALTER TABLE "DB2013_G14"."EVENTS" ADD FOREIGN KEY ("OLYMPICS")
	  REFERENCES "DB2013_G14"."GAMES" ("NAME") ENABLE;
 
  ALTER TABLE "DB2013_G14"."EVENTS" ADD FOREIGN KEY ("DISCIPLINES", "SPORT")
	  REFERENCES "DB2013_G14"."DISCIPLINES" ("NAME", "SPORT") ENABLE;
