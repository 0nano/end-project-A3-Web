DROP TABLE IF EXISTS accident;  
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS descr_athmo;
DROP TABLE IF EXISTS descr_lum;
DROP TABLE IF EXISTS descr_etat_surf;
DROP TABLE IF EXISTS descr_dispo_secu;


CREATE TABLE users (
    mail VARCHAR(255) NOT NULL,
    access_token VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (mail)
);

CREATE TABLE descr_athmo (
    id_athmo INT AUTO_INCREMENT NOT NULL,
    description VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_athmo)
);

CREATE TABLE descr_lum (
    id_lum INT AUTO_INCREMENT NOT NULL,
    description VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_lum)
);

CREATE TABLE descr_etat_surf (
    id_surf INT AUTO_INCREMENT NOT NULL,
    description VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_surf)
);

CREATE TABLE descr_dispo_secu (
    id_secu INT AUTO_INCREMENT NOT NULL,
    description VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_secu)
);


CREATE TABLE accident (
    Num_Acc BIGINT,
    date INT NOT NULL,
    id_code_insee INT NOT NULL,
    ville VARCHAR(255) NOT NULL,
    latitude FLOAT NOT NULL,
    longitude FLOAT NOT NULL,
    descr_grav INT NOT NULL,
    grav_name VARCHAR(255) NOT NULL,
    department_number INT NOT NULL,
    department_name VARCHAR(255) NOT NULL,
    region_number INT NOT NULL,
    region_name VARCHAR(255) NOT NULL,
    descr_athmo INT NOT NULL,
    descr_lum INT NOT NULL,
    descr_etat_surf INT NOT NULL,
    descr_dispo_secu INT NOT NULL,
    users VARCHAR(255) NOT NULL,
    centroid INT NOT NULL,
    PRIMARY KEY (Num_Acc),
    INDEX FK_descr_athmo (descr_athmo),
    INDEX FK_descr_lum (descr_lum),
    INDEX FK_descr_etat_surf (descr_etat_surf),
    INDEX FK_descr_dispo_secu (descr_dispo_secu),
    INDEX FK_users (users),
    INDEX FK_centroid (centroid),
    CONSTRAINT accident_FK_descr_athmo FOREIGN KEY (descr_athmo) REFERENCES descr_athmo (id_athmo),
    CONSTRAINT accident_FK_descr_lum FOREIGN KEY (descr_lum) REFERENCES descr_lum (id_lum),
    CONSTRAINT accident_FK_descr_etat_surf FOREIGN KEY (descr_etat_surf) REFERENCES descr_etat_surf (id_surf),
    CONSTRAINT accident_FK_descr_dispo_secu FOREIGN KEY (descr_dispo_secu) REFERENCES descr_dispo_secu (id_secu),
    CONSTRAINT accident_FK_users FOREIGN KEY (users) REFERENCES users (mail)
) ENGINE=InnoDB;
