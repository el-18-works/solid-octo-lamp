
-- DROP USER IF EXISTS 'l18'@'localhost';
-- DROP USER IF EXISTS 'luckxa'@'localhost';

CREATE USER IF NOT EXISTS 'l18'@'localhost'
	IDENTIFIED BY '@';
GRANT ALL ON *.*
	TO 'l18'@'localhost';
-- PARA CREATE PROCEDURE/FUNCTION :
GRANT SUPER ON *.*
	TO 'l18'@'localhost';

CREATE USER IF NOT EXISTS 
	'luckxa'@'localhost' 
	IDENTIFIED BY 'MyLinka';
GRANT SELECT ON *.*
	TO 'luckxa'@'localhost';
GRANT ALL ON l18.*
	TO 'luckxa'@'localhost';
GRANT ALL ON luckxa.*
	TO 'luckxa'@'localhost';

