DROP TABLE IF EXISTS accident;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS descr_athmo;
DROP TABLE IF EXISTS descr_lum;
DROP TABLE IF EXISTS descr_etat_surf;
DROP TABLE IF EXISTS descr_dispo_secu;

CREATE TABLE users (
    mail VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    nom VARCHAR(255) NOT NULL,
    access_token VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (mail)
);

CREATE TABLE descr_athmo (
    id_athmo SERIAL PRIMARY KEY,
    description VARCHAR(255) NOT NULL
);

CREATE TABLE descr_lum (
    id_lum SERIAL PRIMARY KEY,
    description VARCHAR(255) NOT NULL
);

CREATE TABLE descr_etat_surf (
    id_surf SERIAL PRIMARY KEY,
    description VARCHAR(255) NOT NULL
);

CREATE TABLE descr_dispo_secu (
    id_secu SERIAL PRIMARY KEY,
    description VARCHAR(255) NOT NULL
);

CREATE TABLE accident (
    id_accident SERIAL PRIMARY KEY,
    Num_Acc BIGINT,
    date DATE,
    age INT,
    id_code_insee INT,
    ville VARCHAR(255) NOT NULL,
    latitude FLOAT NOT NULL,
    longitude FLOAT NOT NULL,
    descr_grav INT,
    department_number INT,
    department_name VARCHAR(255),
    region_number INT,
    descr_athmo INT NOT NULL,
    descr_lum INT NOT NULL,
    descr_etat_surf INT NOT NULL,
    descr_dispo_secu INT NOT NULL,
    CONSTRAINT accident_FK_descr_athmo FOREIGN KEY (descr_athmo) REFERENCES descr_athmo (id_athmo),
    CONSTRAINT accident_FK_descr_lum FOREIGN KEY (descr_lum) REFERENCES descr_lum (id_lum),
    CONSTRAINT accident_FK_descr_etat_surf FOREIGN KEY (descr_etat_surf) REFERENCES descr_etat_surf (id_surf),
    CONSTRAINT accident_FK_descr_dispo_secu FOREIGN KEY (descr_dispo_secu) REFERENCES descr_dispo_secu (id_secu)
);
