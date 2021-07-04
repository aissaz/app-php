CREATE TABLE client
(
    num            VARCHAR(25) PRIMARY KEY,
    mail           VARCHAR(50) NOT NULL,
    nom            VARCHAR(25) NOT NULL,
    prenom         VARCHAR(25) NOT NULL,
    date_naiss     date,
    pays           VARCHAR(25),
    mot_passe      VARCHAR(25),
    designation_op VARCHAR(25)

);
CREATE TABLE operateur
(
    designation_op VARCHAR(25) PRIMARY KEY,
    adresse        VARCHAR(100),
    pays           VARCHAR(25),
    mail           VARCHAR(50),
    num            VARCHAR(25),
    fb             VARCHAR(50),
    linkedin       VARCHAR(50)
);
CREATE TABLE facture
(
    num_facture    INT AUTO_INCREMENT PRIMARY KEY,
    montant        FLOAT NOT NULL,
    date           DATE,
    num_client     VARCHAR(25),
    num_abonnement INT


);
CREATE TABLE offre
(
    designation_offre VARCHAR(25) PRIMARY KEY,
    appels_op         VARCHAR(50) NOT NULL,
    sms_op            VARCHAR(50) NOT NULL,
    appels_autre_op   VARCHAR(50) NOT NULL,
    sms_autre_op      VARCHAR(50) NOT NULL,
    internet          VARCHAR(50) NOT NULL,
    prix              FLOAT       NOT NULL,
    designation_op    VARCHAR(25)

);
CREATE TABLE abonnement
(
    num_abonnement    INT AUTO_INCREMENT PRIMARY KEY,
    date              date NOT NULL,
    designation_offre VARCHAR(25),
    num_client        VARCHAR(25)


);
ALTER table client
    ADD CONSTRAINT fk_client_operateur FOREIGN KEY (designation_op) REFERENCES operateur (designation_op);
ALTER table facture
    ADD CONSTRAINT fk_facture_client FOREIGN KEY (num_client) REFERENCES client (num);
ALTER table facture
    ADD CONSTRAINT fk_facture_abonnement FOREIGN KEY (num_abonnement) REFERENCES abonnement (num_abonnement);
ALTER table offre
    ADD CONSTRAINT fk_offre_operateur FOREIGN KEY (designation_op) REFERENCES operateur (designation_op);
ALTER table abonnement
    ADD CONSTRAINT fk_abonnement_client FOREIGN KEY (num_client) REFERENCES client (num);
ALTER table abonnement
    ADD CONSTRAINT fk_abonnement_offre FOREIGN KEY (designation_offre) REFERENCES offre (designation_offre);
