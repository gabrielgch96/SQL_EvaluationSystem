
DELIMITER $$

DROP SCHEMA IF EXISTS avions $$
CREATE SCHEMA IF NOT EXISTS avions DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci $$
USE avions $$


CREATE TABLE IF NOT EXISTS avion (
  id_avion INT NOT NULL AUTO_INCREMENT,
  date_mise_service DATE NULL,
  type VARCHAR(10) NULL,
  PRIMARY KEY (id_avion))
ENGINE = InnoDB $$


CREATE TABLE IF NOT EXISTS ville (
  id_ville INT NOT NULL AUTO_INCREMENT,
  nom VARCHAR(45) NOT NULL,
  PRIMARY KEY (id_ville),
  UNIQUE INDEX nom_UNIQUE (nom ASC))
ENGINE = InnoDB $$


CREATE TABLE IF NOT EXISTS aeroport (
  id_aeroport VARCHAR(4) NOT NULL,
  nom VARCHAR(45) NOT NULL,
  id_ville INT NOT NULL,
  PRIMARY KEY (id_aeroport),
  UNIQUE INDEX nom_UNIQUE (nom ASC),
  CONSTRAINT fk_aeroport_ville
    FOREIGN KEY (id_ville)
    REFERENCES ville (id_ville))
ENGINE = InnoDB $$


CREATE TABLE IF NOT EXISTS pilote (
  id_pilote INT NOT NULL AUTO_INCREMENT,
  prenom VARCHAR(45) NOT NULL,
  nom VARCHAR(45) NOT NULL,
  salaire DECIMAL(7, 2) NOT NULL,
  prime DECIMAL(6,2),
  PRIMARY KEY (id_pilote),
  UNIQUE INDEX pilote_nom_prenom_unique (prenom ASC, nom ASC))
ENGINE = InnoDB $$


CREATE TABLE IF NOT EXISTS vol (
  id_vol INT PRIMARY KEY AUTO_INCREMENT,
  id_pilote INT NOT NULL,
  id_avion INT NOT NULL,
  date_depart DATETIME NOT NULL,
  date_arrivee DATETIME NOT NULL,
  id_aeroport_depart VARCHAR(4) NOT NULL,
  id_aeroport_arrivee VARCHAR(4) NOT NULL,
  UNIQUE INDEX vol_pilote_date_unique (id_pilote, date_depart),
  UNIQUE INDEX vol_avion_date_unique (id_avion, date_depart),
  CONSTRAINT fk_vol_pilote
    FOREIGN KEY (id_pilote)
    REFERENCES pilote (id_pilote),
  CONSTRAINT fk_vol_avion
    FOREIGN KEY (id_avion)
    REFERENCES avion (id_avion),
  CONSTRAINT fk_vol_aeroport_depart
    FOREIGN KEY (id_aeroport_depart)
    REFERENCES aeroport (id_aeroport),
  CONSTRAINT fk_vol_aeroport_arrivee
    FOREIGN KEY (id_aeroport_arrivee)
    REFERENCES aeroport (id_aeroport))
ENGINE = InnoDB $$

DROP PROCEDURE IF EXISTS avions_reset$$
CREATE DEFINER=root@localhost PROCEDURE avions_reset()
BEGIN
  -- Desactiver les contraintes d'intégrité
  SET FOREIGN_KEY_CHECKS = 0;
  -- Vider les tables et mettre leur auto_increment à 1
  TRUNCATE TABLE vol;
  TRUNCATE TABLE avion;
  TRUNCATE TABLE aeroport;
  TRUNCATE TABLE ville;
  TRUNCATE TABLE pilote;
  -- Réactiver les contraintes d'intégrité
  SET FOREIGN_KEY_CHECKS = 1;

  INSERT INTO avion (id_avion, date_mise_service, type) VALUES 
  (1, '1992-10-20', 'A380'),
  (2, '2002-12-20', 'A310'),
  (3, '2015-04-04', 'A380');
  
  INSERT INTO ville (id_ville, nom) VALUES 
  (1, 'Paris'),
  (2, 'Nice'),
  (3, 'New York'),
  (4, 'London');
  
  INSERT INTO aeroport(id_aeroport, nom, id_ville) VALUES
  ('CDG', 'Charles de Gaulle', 1),
  ('ORY', 'Orly', 1),
  ('OACA', 'Nice ville', 2),
  ('JFK', 'John Fizgerald Kennedy', 3),
  ('HEA', 'Heathrow', 4);
  
  INSERT INTO pilote(id_pilote, prenom, nom, salaire, prime) VALUES
  (1, 'Manuel', 'Moulin', 6000, NULL),
  (2, 'Mireille', 'Dujardin', 6500, 1000),
  (3, 'Mathieu', 'Lenormand', 5500, 500),
  (4, 'Christine', 'Dupont', 6500, NULL);
  
  INSERT INTO vol(id_vol, id_pilote, id_avion, date_depart, date_arrivee, id_aeroport_depart, id_aeroport_arrivee) VALUES
  (1, 1, 1, '2015-04-12 12:20:00', '2015-04-12 18:20:00', 'CDG', 'JFK'),
  (2, 1, 3, '2015-04-13 10:20:00', '2015-04-13 18:20:00', 'JFK', 'ORY'),
  (3, 1, 2, '2015-04-14 19:00:00', '2015-04-14 20:20:00', 'ORY', 'HEA'),
  (4, 1, 1, '2015-04-15 12:20:00', '2015-04-15 18:20:00', 'CDG', 'JFK'),
  (5, 2, 2, '2015-04-12 12:20:00', '2015-04-12 13:40:00', 'ORY', 'OACA'),
  (6, 2, 3, '2015-05-13 09:00:00', '2015-05-13 10:00:00', 'CDG', 'HEA'),
  (7, 2, 3, '2015-04-16 12:20:00', '2015-04-16 18:20:00', 'CDG', 'JFK'),
  (8, 3, 1, '2015-05-14 12:00:00', '2015-05-14 13:20:00', 'CDG', 'OACA');
  
  COMMIT;
END$$

CALL avions_reset() $$