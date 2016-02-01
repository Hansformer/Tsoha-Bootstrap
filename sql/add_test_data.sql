-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO Asiakas (Nimimerkki, Salasana, Email, Syntymapaiva, Sukupuoli, Paikkakunta, Yllapitaja, Paritele)
VALUES ('Killermaster', 'salainen', 'killer.master@gnail.com', DATE '1994-01-01', true, 'Tampere', false, false),
	   ('Admin', 'admin', 'admin@adminer.biz', DATE '2001-03-12', true, 'Admintown', true, false),
	   ('Paritele', 'admin', 'valvonta@paritele.oy', DATE '1980-05-02', false, 'Helsinki', false, true),
	   ('CutesyKitty', 'tosisalainen', 'kittens@gnail.com', DATE '1992-02-02', false, 'Espoo', false, false);
	   
INSERT INTO Viesti (LahettavaID, VastaanottavaID, Sisalto, Lahetysaika)
VALUES (3, 1, 'lopeta ahdistelu', TIMESTAMP '2016-01-01 00:01:02'),
	   (1, 3, 'en >:)', NOW());
	   
INSERT INTO EsittelysivuJulkinen (AsiakasID, Sisalto)
VALUES (1, 'Moi kiki, oon best'),
	   (2, 'Olen ADMINKING');
	   
INSERT INTO  EsittelysivuSalainen (AsiakasID, KenelleID, Sisalto)
VALUES (1, 2, 'Tätä ei nää kukaan paitsi SÄÄ ;)');