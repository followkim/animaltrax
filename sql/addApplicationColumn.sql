use pixie;


alter table Animal 
add column isHypo TINYINT NOT NULL default 0;

alter table Application 
add column needHypo TINYINT NOT NULL default 0;

alter table Application 
add column closed TINYINT NOT NULL default 0;

alter table Application 
add column rank int default 3;