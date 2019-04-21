
CREATE SCHEMA IF NOT EXISTS l18;
USE l18;

-- my diary txt table.

-- a annus.
-- m mensis.
-- d dies.
CREATE TABLE IF NOT EXISTS luckxa_mydiary_txt (
	id INT AUTO_INCREMENT NOT NULL,
	a YEAR(4) NOT NULL,
	m INT NOT NULL,
	d INT DEFAULT 0,
	h VARCHAR(200),
	txt TEXT,
	PRIMARY KEY(id),
	FULLTEXT(txt)	
) DEFAULT CHARSET="utf8";


DROP TABLE luckxa_mydiary_txt_test;
CREATE TABLE IF NOT EXISTS luckxa_mydiary_txt_test LIKE luckxa_mydiary_txt;
INSERT luckxa_mydiary_txt_test SELECT * FROM luckxa_mydiary_txt;

SHOW TABLES;

