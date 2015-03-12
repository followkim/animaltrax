USE `pixie`;
DROP procedure IF EXISTS `matchAnimals`;

DELIMITER $$
USE `pixie`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `matchAnimals`(IN  inApplicationID int, IN  inAnimalID int)
BEGIN
select  * 
FROM 
(
	select a.animalID, a.animalName, a.breed, a.species, a.gender, a.activityLevel, a.personalityID, a.note, a.kids, a.dogs, a.cats, a.isHypo,
		vs.vitalValue as weight, t.transferTypeID, tt.transferName,
		(date_format(from_days((to_days(now()) - to_days(`a`.`estBirthdate`))), '%Y') + 0) AS `age`
	From Animal a
	LEFT JOIN (
		select vs.animalID, vs.vitalValue,  max(vs.vitalDateTime) as vitalDateTime
		FROM VitalSign vs
		WHERE vs.vitalSignTypeID = 7
		GROUP BY vs.animalID
	) w on w.animalID=a.animalID
	LEFT JOIN VitalSign vs ON vs.animalID = w.animalID and vs.vitalDateTime=w.vitalDateTime and vs.vitalSignTypeID = 7
	INNER JOIN (
		select t.animalID, max(t.transferDate) as transferDate
		FROM Transfer t
		group by t.animalID
	) cp on cp.animalID = a.animalID  
	INNER JOIN Transfer t on t.animalID = cp.animalID and t.transferDate=cp.transferDate 
	INNER JOIN TransferType tt on tt.transferTypeID = t.transferTypeID
) al
INNER JOIN Application w ON (
	w.closed = 0 AND 
	(al.species = w.species) AND
	((al.cats = 'Y' and w.numCats > 0) or (w.numCats = 0)) AND 
	((al.dogs = 'Y' and w.numDogs > 0) or (w.numDogs = 0)) AND 
	((al.kids = 'Y' and w.numKids > 0) or (w.numKids = 0)) AND 
	((al.isHypo = 1) or (w.needHypo <> 1)) AND 
	((al.gender = w.gender) or (w.gender = '')) AND 
	((al.personalityID = w.personalityID) or (w.personalityID = '') or (w.personalityID = 0)) AND 
	((al.age >= w.minAge) and (al.age <= w.maxAge)) AND
	((al.weight >= w.minWeight) and (al.weight <= w.maxWeight)) AND
	((al.activityLevel >= w.minActivityLevel) and (al.activityLevel <= w.maxActivityLevel)) AND
	(al.transferTypeID in (1, 5))
)
INNER JOIN Person p on w.personID = p.personID
WHERE ((w.applicationID = inApplicationID) OR (al.animalID = inAnimalID))
;
END$$

DELIMITER ;

