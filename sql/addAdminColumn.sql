ALTER TABLE `pixie`.`Users` 
ADD COLUMN `isAdmin` BIT NOT NULL AFTER `password`;

update Users set isAdmin = 1 where userID = 1;
update Users set isAdmin = 1 where userID = 2;


