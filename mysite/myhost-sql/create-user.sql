CREATE USER IF NOT EXISTS 'l18'@'%' IDENTIFIED BY '@';
CREATE USER IF NOT EXISTS 'luckxa'@'%' IDENTIFIED BY 'MyLinka';
GRANT SUPER ON *.* TO 'l18'@'%';
GRANT SELECT ON *.* TO 'luckxa'@'%';
GRANT ALL ON l18.* TO 'luckxa'@'%';
GRANT ALL ON luckxa.* TO 'luckxa'@'%';
GRANT ALL ON l18.* TO 'l18'@'%';
