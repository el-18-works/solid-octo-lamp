-- 
-- 
-- l18 schema.
-- 
-- 
CREATE SCHEMA IF NOT EXISTS l18;
USE l18;

-- 
-- 
-- ens game 2d suichu res table.
-- 
-- 
CREATE TABLE ens_game_2d_suichu_res (
	id INT AUTO_INCREMENT NOT NULL,
	crc32 VARCHAR(8), NOT NULL,
	mimetype VARCHAR(256) NOT NULL,
	unit VARCHAR(8), NOT NULL,
	comment TEXT,
	PRIMARY KEY(id),
) DEFAULT CHARSET = 'UTF-8';

