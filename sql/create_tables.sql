-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE Asiakas
(
	asiakasid serial PRIMARY KEY,
	nimimerkki varchar(32) NOT NULL UNIQUE,
	salasana varchar(64) NOT NULL,
	email varchar(64) NOT NULL,
	syntymapaiva DATE,
	sukupuoli boolean,
	paikkakunta varchar(128) NOT NULL, 
	yllapitaja boolean DEFAULT FALSE,
	paritele boolean DEFAULT FALSE
);

CREATE TABLE Viesti
(
	viestiID serial PRIMARY KEY,
	lahettavaID integer references Asiakas(asiakasid),
	vastaanottavaID integer references Asiakas(asiakasid),
	sisalto varchar(512) NOT NULL,
	lahetysaika timestamp NOT NULL
);

CREATE TABLE Esittelysivujulkinen
(
	sivuid serial PRIMARY KEY,
	asiakasid integer references  Asiakas(asiakasid),
	sisalto varchar(1024) NOT NULL
);

CREATE TABLE Esittelysivusalainen
(
	sivuid serial PRIMARY KEY,
	asiakasid integer references Asiakas(asiakasid),
	kenelleid integer references Asiakas(asiakasid),
	sisalto varchar(256) NOT NULL
);