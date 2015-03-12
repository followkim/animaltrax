USE `pixie`;
DROP procedure IF EXISTS `FindApplication`;

DELIMITER $$
USE `pixie`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `FindApplication`(
	IN inName varchar(100),
	IN inBreed varchar(200),
	IN inSpecies char,
	IN showClosed bit
)
BEGIN
	SELECT a.*, p.personID, p.firstName, p.lastName from Application a
    INNER JOIN Person p on a.personID = p.personID
	WHERE
	(
		(p.firstName like concat('%', inName, '%')) OR 
		(p.lastName  like concat('%', inName, '%')) OR 
		(concat('%', p.firstName, '%', p.lastName, '%') like concat('%', inName, '%')) OR 
		(inName like '')
	) AND (
        (a.breed like concat('%', inBreed, '%')) OR (inBreed like '')
    ) AND (
        (a.species like inSpecies) OR (inSpecies like '')
	) AND  (
        showClosed=1 OR (a.closed = 0)
	)
    ORDER BY a.applicationDate
        ;
END$$

DELIMITER ;