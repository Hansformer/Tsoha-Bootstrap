-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE Asiakas
(
	AsiakasID SERIAL PRIMARY KEY,
	Nimimerkki varchar(32) NOT NULL UNIQUE,
	Salasana varchar(64) NOT NULL,
	Email varchar(64) NOT NULL,
	Syntymapaiva DATE,
	Sukupuoli boolean,
	Paikkakunta varchar(128) NOT NULL, 
	Yllapitaja boolean DEFAULT FALSE,
	Paritele boolean DEFAULT FALSE
);

CREATE TABLE Viesti
(
	ViestiID integer SERIAL PRIMARY KEY,
	LahettavaID integer references Asiakas(AsiakasID),
	VastaanottavaID integer references Asiakas(AsiakasID),
	Sisalto varchar(512) NOT NULL,
	Lahetysaika timestamp NOT NULL
);

CREATE TABLE EsittelysivuJulkinen
(
	SivuID integer SERIAL PRIMARY KEY,
	AsiakasID integer references  Asiakas(AsiakasID),
	Sisalto varchar(1024) NOT NULL
);

CREATE TABLE EsittelysivuSalainen
(
	SivuID integer SERIAL PRIMARY KEY,
	AsiakasID integer references Asiakas(AsiakasID),
	KenelleID integer references Asiakas(AsiakasID),
	Sisalto varchar(256) NOT NULL
);