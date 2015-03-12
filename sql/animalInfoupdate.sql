USE `pixie`;
CREATE 
     OR REPLACE ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER
VIEW `AnimalInfo` AS
    SELECT 
        `a`.`animalID` AS `animalID`,
        `a`.`animalName` AS `animalName`,
        `a`.`breed` AS `breed`,
        `a`.`markings` AS `markings`,
        `a`.`activityLevel` AS `activityLevel`,
        (CASE `a`.`species`
            WHEN 'D' THEN 'Dog'
            WHEN 'C' THEN 'Cat'
            WHEN 'O' THEN 'Other'
        END) AS `species`,
        (CASE `a`.`gender`
            WHEN 'F' THEN 'Female'
            WHEN 'M' THEN 'Male'
            WHEN 'O' THEN 'Other/Unknown'
        END) AS `gender`,
        (CASE `a`.`isFixed`
            WHEN '1' THEN 'Yes'
            WHEN '0' THEN 'No'
        END) AS `isFixed`,
		(CASE `a`.`isHypo`
            WHEN '1' THEN 'Yes'
            WHEN '0' THEN 'No'
        END) AS `isHypo`,
        (CASE `a`.`kids`
            WHEN 'Y' THEN 'Yes'
            WHEN 'N' THEN 'No'
        END) AS `kids`,
        (CASE `a`.`cats`
            WHEN 'Y' THEN 'Yes'
            WHEN 'N' THEN 'No'
        END) AS `cats`,
        (CASE `a`.`dogs`
            WHEN 'Y' THEN 'Yes'
            WHEN 'N' THEN 'No'
        END) AS `dogs`,
        `a`.`estBirthdate` AS `estBirthdate`,
        (DATE_FORMAT(FROM_DAYS((TO_DAYS(NOW()) - TO_DAYS(`a`.`estBirthdate`))),
                '%Y') + 0) AS `age`,
        `a`.`note` AS `note`,
        `a`.`microchipNumber` AS `microchipNumber`,
        `a`.`dateImplanted` AS `dateImplanted`,
        `mt`.`microchipName` AS `microchipName`,
        `py`.`personality` AS `personality`,
        `as`.`adoptionStatus` AS `adoptionStatus`,
        `a`.`url` AS `url`
    FROM
        (((`Animal` `a`
        LEFT JOIN `MicrochipType` `mt` ON ((`mt`.`microchipTypeID` = `a`.`microchipTypeID`)))
        LEFT JOIN `Personality` `py` ON ((`py`.`personalityID` = `a`.`personalityID`)))
        LEFT JOIN `AdoptionStatus` `as` ON ((`as`.`adoptionStatusID` = `a`.`adoptionStatusID`)));
