USE l18;;
-- DROP TABLE luckxa_mydiary_txt;;
CREATE TABLE IF NOT EXISTS luckxa_mydiary_txt (;
id INT AUTO_INCREMENT NOT NULL,;
annus. a INT NOT NULL,;
mensis. m INT NOT NULL,;
dies. d INT DEFAULT 0,;
h VARCHAR(200),;
txt TEXT,;
PRIMARY KEY(id),;
FULLTEXT(txt);
) DEFAULT CHARSET="utf8";
