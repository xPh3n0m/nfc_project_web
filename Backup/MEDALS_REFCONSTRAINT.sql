--------------------------------------------------------
--  Ref Constraints for Table MEDALS
--------------------------------------------------------

  ALTER TABLE "DB2013_G14"."MEDALS" ADD FOREIGN KEY ("OLYMPICS", "DISCIPLINES", "SPORT")
	  REFERENCES "DB2013_G14"."EVENTS" ("OLYMPICS", "DISCIPLINES", "SPORT") ENABLE;
 
  ALTER TABLE "DB2013_G14"."MEDALS" ADD FOREIGN KEY ("AID", "OLYMPICS", "COUNTRY", "SPORT")
	  REFERENCES "DB2013_G14"."PARTICIPANTS" ("AID", "OLYMPICS", "COUNTRY", "SPORT") ENABLE;
