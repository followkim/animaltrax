CREATE TABLE DogSize (
  dogSizeName varchar(20) NOT NULL,
  minSize int,
  maxSize int,
  PRIMARY KEY (minSize, maxSize)
) ENGINE=InnoDB DEFAULT CHARSET=big5;


INSERT INTO `pixie`.`DogSize`
(`dogSizeName`,
`minSize`,
`maxSize`)
VALUES
("Toy", 		0, 10),
("Small", 		10, 25),
("Medium", 		25, 60),
("Large", 		60, 150),
("ExtraLarge", 	150, 400);

