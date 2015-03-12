USE `pixie`;
DROP procedure IF EXISTS `pixieVaccinations`;

DELIMITER $$
USE `pixie`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `pixieVaccinations`(inDate date)
BEGIN
SELECT m.*, p.*, a.animalID, a.animalName 
FROM (
	SELECT medicationID, animalID, max(startDate) startDate 
	FROM Prescription 
	GROUP BY medicationID, animalID
) maxDate
INNER JOIN Medication m on m.medicationID = maxDate.medicationID
INNER JOIN Prescription p on p.startDate = maxDate.startDate and maxDate.animalID = p.animalID and p.medicationID = maxDate.medicationID
INNER JOIN Animal a on maxDate.animalID = a.animalID
INNER JOIN CurrentTransfer ct on ct.animalID = a.animalID and ct.pixieResponsible in ('Y', '')
WHERE p.nextDose is not null  and p.nextDose <= inDate
order by p.nextDose;
END$$

DELIMITER ;

