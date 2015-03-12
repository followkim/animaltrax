CREATE DEFINER=`root`@`localhost` PROCEDURE `FindPerson`(	
	IN inName varchar(100),
	IN inEmail varchar(20),
	IN inTelephone varchar(20),
	IN inPositionTypeID int,
	IN inIsOrg bit
)
BEGIN
	SELECT p.* from Person p
	WHERE
	(
		(p.firstName like concat('%', inName, '%')) OR 
		(p.lastName  like concat('%', inName, '%')) OR 
		(concat('%', p.firstName, '%', p.lastName, '%') like concat('%', inName, '%')) OR 
		(p.secondary  like concat('%', inName, '%')) OR 
		(inName like '')
	) AND (
        p.email like concat('%', inEmail, '%') OR (inEmail like '')
    ) AND (
		(p.cellPhone like concat('%', inTelephone, '%')) OR
		(p.homePhone like concat('%', inTelephone, '%')) OR
		(p.workPhone like concat('%', inTelephone, '%')) OR
		(inTelephone like '')
	) AND  (
        inIsOrg=p.isOrg OR (inIsOrg=0)
    ) AND (
		(inPositionTypeID in (select positionTypeID from PersonPosition where personID = p.personID and positionTypeID = inPositionTypeID)) 
		OR (inPositionTypeID=0)
	) and p.personID > 0;
END