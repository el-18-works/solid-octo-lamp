
CREATE USER IF NOT EXISTS 'luckxa'@'localhost'
	IDENTIFIED BY 'mylinka';
GRANT ALL
	ON *.*
	TO 'luckxa'@'localhost';

CREATE USER IF NOT EXISTS 'l18'@'localhost'
	IDENTIFIED BY '';
GRANT SELECT
	ON *.*
	TO 'l18'@'localhost';
GRANT ALL
	ON l18.*
	TO 'l18'@'localhost';

