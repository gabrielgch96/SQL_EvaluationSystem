-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema sql_skills_fall2019
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS sql_skills_fall2019 ;

-- -----------------------------------------------------
-- Schema sql_skills_fall2019
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS sql_skills_fall2019 DEFAULT CHARACTER SET utf8 ;
USE sql_skills_fall2019 ;

-- -----------------------------------------------------
-- Table quiz_db
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS quiz_db (
  db_name VARCHAR(45) NOT NULL,
  diagram_path VARCHAR(255) NULL DEFAULT NULL,
  creation_script_path VARCHAR(255) NULL DEFAULT NULL,
  description TEXT NULL DEFAULT NULL,
  PRIMARY KEY (db_name))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table person
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS person (
  person_id INT(11) NOT NULL AUTO_INCREMENT,
  email VARCHAR(45) NOT NULL,
  pwd VARCHAR(255) NOT NULL,
  name VARCHAR(45) NOT NULL,
  first_name VARCHAR(45) NOT NULL,
  token VARCHAR(45) NULL DEFAULT NULL,
  created_at DATETIME NOT NULL,
  validated_at DATETIME NULL DEFAULT NULL,
  is_trainer TINYINT(1) NOT NULL,
  PRIMARY KEY (person_id),
  UNIQUE INDEX email_UNIQUE (email ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table sql_quiz
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS sql_quiz (
  quiz_id INT(11) NOT NULL AUTO_INCREMENT,
  title VARCHAR(45) NOT NULL,
  is_public TINYINT(1) NOT NULL DEFAULT '0',
  author_id INT(11) NOT NULL,
  db_name VARCHAR(45) NOT NULL,
  PRIMARY KEY (quiz_id),
  INDEX fk_sql_quiz_trainer1_idx (author_id ASC),
  INDEX fk_sql_quiz_quiz_db1_idx (db_name ASC),
  CONSTRAINT fk_sql_quiz_quiz_db
    FOREIGN KEY (db_name)
    REFERENCES quiz_db (db_name)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_sql_quiz_trainer
    FOREIGN KEY (author_id)
    REFERENCES person (person_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table usergroup
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS usergroup (
  group_id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NOT NULL,
  creator_id INT(11) NOT NULL,
  created_at DATETIME NULL DEFAULT NULL,
  is_closed TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (group_id),
  UNIQUE INDEX name_UNIQUE (name ASC),
  INDEX fk_usergroup_trainer1_idx (creator_id ASC),
  CONSTRAINT fk_usergroup_trainer
    FOREIGN KEY (creator_id)
    REFERENCES person (person_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table evaluation
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS evaluation (
  evaluation_id INT(11) NOT NULL COMMENT 'evaluation IS A training, so training_id is the PK',
  group_id INT(11) NOT NULL,
  quiz_id INT(11) NOT NULL,
  scheduled_at DATETIME NOT NULL,
  ending_at DATETIME NOT NULL,
  precorrected_at DATETIME NULL DEFAULT NULL,
  corrected_at DATETIME NULL DEFAULT NULL COMMENT 'completely corrected by the trainer at',
  PRIMARY KEY (evaluation_id),
  INDEX fk_exam_class1_idx (group_id ASC),
  INDEX fk_evaluation_sql_quiz1_idx (quiz_id ASC),
  CONSTRAINT fk_evaluation_sql_quiz
    FOREIGN KEY (quiz_id)
    REFERENCES sql_quiz (quiz_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_evaluation_usergroup
    FOREIGN KEY (group_id)
    REFERENCES usergroup (group_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table group_member
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS group_member (
  person_id INT(11) NOT NULL,
  group_id INT(11) NOT NULL,
  validated_at DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (person_id, group_id),
  INDEX fk_user_has_class_class1_idx (group_id ASC),
  INDEX fk_user_has_class_user1_idx (person_id ASC),
  CONSTRAINT fk_group_member_usergroup
    FOREIGN KEY (group_id)
    REFERENCES usergroup (group_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_group_member_user
    FOREIGN KEY (person_id)
    REFERENCES person (person_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table sheet
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS sheet (
  trainee_id INT(20) NOT NULL,
  evaluation_id INT(11) NOT NULL,
  started_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  ended_at DATETIME NULL DEFAULT NULL COMMENT 'when the trainee terminates his sheet',
  corrected_at DATETIME NULL DEFAULT NULL COMMENT 'validated by the trainer at',
  PRIMARY KEY (trainee_id, evaluation_id),
  INDEX fk_Test_user1_idx (trainee_id ASC),
  INDEX fk_sheet_evaluation1_idx (evaluation_id ASC),
  CONSTRAINT fk_sheet_evaluation
    FOREIGN KEY (evaluation_id)
    REFERENCES evaluation (evaluation_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_sheet_trainee
    FOREIGN KEY (trainee_id)
    REFERENCES person (person_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table theme
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS theme (
  theme_id INT(11) NOT NULL AUTO_INCREMENT,
  label VARCHAR(255) NOT NULL,
  PRIMARY KEY (theme_id),
  UNIQUE INDEX label_UNIQUE (label ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table sql_question
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS sql_question (
  question_id INT(20) NOT NULL AUTO_INCREMENT,
  db_name VARCHAR(45) NOT NULL,
  question_text VARCHAR(255) NOT NULL,
  correct_answer TEXT NOT NULL,
  correct_result TEXT NULL DEFAULT NULL,
  is_public TINYINT(1) NOT NULL DEFAULT '0',
  theme_id INT(11) NOT NULL,
  author_id INT(11) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT NOW(),
  PRIMARY KEY (question_id),
  INDEX fk_sql_question_theme1_idx (theme_id ASC),
  INDEX fk_sql_question_quiz_db1_idx (db_name ASC),
  INDEX fk_sql_question_trainer1_idx (author_id ASC),
  CONSTRAINT fk_sql_question_quiz_db
    FOREIGN KEY (db_name)
    REFERENCES quiz_db (db_name)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_sql_question_theme
    FOREIGN KEY (theme_id)
    REFERENCES theme (theme_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_sql_question_trainer
    FOREIGN KEY (author_id)
    REFERENCES person (person_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table sheet_answer
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS sheet_answer (
  question_id INT(20) NOT NULL,
  trainee_id INT(20) NOT NULL,
  evaluation_id INT(11) NOT NULL,
  answer TEXT NULL DEFAULT NULL COMMENT 'query written by the trainee',
  result TEXT NULL DEFAULT NULL,
  given_at DATETIME NULL,
  gives_correct_result TINYINT(1) NULL DEFAULT NULL COMMENT 'validated by the trainer (null if not yet validated or invalidated)',
  PRIMARY KEY (question_id, trainee_id, evaluation_id),
  INDEX fk_answer_question1_idx (question_id ASC),
  INDEX fk_sheet_answer_sheet1_idx (evaluation_id ASC, trainee_id ASC),
  CONSTRAINT fk_answer_question
    FOREIGN KEY (question_id)
    REFERENCES sql_question (question_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_sheet_answer_sheet
    FOREIGN KEY (evaluation_id , trainee_id)
    REFERENCES sheet (evaluation_id , trainee_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table sql_quiz_question
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS sql_quiz_question (
  question_id INT(20) NOT NULL,
  quiz_id INT(11) NOT NULL,
  rank INT(11) NOT NULL COMMENT 'rank of the question in the sql_quiz',
  PRIMARY KEY (question_id, quiz_id),
  INDEX fk_Quizz_has_Question_Question1_idx (question_id ASC),
  INDEX fk_question_quiz1_idx (quiz_id ASC),
  CONSTRAINT fk_quiz_question_quiz
    FOREIGN KEY (quiz_id)
    REFERENCES sql_quiz (quiz_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_quiz_question_question
    FOREIGN KEY (question_id)
    REFERENCES sql_question (question_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table training
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS training (
  training_id INT NOT NULL AUTO_INCREMENT,
  started_at DATETIME NOT NULL DEFAULT now(),
  ended_at DATETIME NULL,
  trainee_id INT(11) NOT NULL,
  quiz_id INT(11) NOT NULL,
  theme_id INT(11) NULL,
  PRIMARY KEY (training_id),
  INDEX fk_training_trainee_idx (trainee_id ASC),
  INDEX fk_training_sql_quiz_idx (quiz_id ASC),
  INDEX fk_training_theme_idx (theme_id ASC),
  CONSTRAINT fk_training_trainee
    FOREIGN KEY (trainee_id)
    REFERENCES person (person_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_training_sql_quiz
    FOREIGN KEY (quiz_id)
    REFERENCES sql_quiz (quiz_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_training_theme
    FOREIGN KEY (theme_id)
    REFERENCES theme (theme_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table training_answer
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS training_answer (
  training_id INT NOT NULL,
  question_id INT(20) NOT NULL,
  answer TEXT NULL,
  result TEXT NULL COMMENT '	',
  given_at DATETIME NULL,
  rank INT(11) NOT NULL,
  PRIMARY KEY (training_id, question_id),
  INDEX fk_training_sql_question_sql_question1_idx (question_id ASC),
  INDEX fk_training_sql_question_training1_idx (training_id ASC),
  CONSTRAINT fk_training_answer_training
    FOREIGN KEY (training_id)
    REFERENCES training (training_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_training_answer_question
    FOREIGN KEY (question_id)
    REFERENCES sql_question (question_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



-- -----------------------------------------------------
-- procedure sql_skills_reset
-- -----------------------------------------------------

DELIMITER $$
USE sql_skills_fall2019$$
CREATE DEFINER=root@localhost PROCEDURE sql_skills_reset()
BEGIN
  DECLARE v_date DATETIME;
  SET v_date = NOW();
   -- Désactiver les contraintes d'intégrité référentielle pour les TRUNCATE
	SET FOREIGN_KEY_CHECKS = 0;
	TRUNCATE TABLE evaluation;
	TRUNCATE TABLE usergroup;
	TRUNCATE TABLE group_member;
	TRUNCATE TABLE sheet;
	TRUNCATE TABLE sheet_answer;
	TRUNCATE TABLE sql_question;
	TRUNCATE TABLE sql_quiz;
	TRUNCATE TABLE sql_quiz_question;
	TRUNCATE TABLE theme;
	TRUNCATE TABLE training;
  TRUNCATE TABLE training_answer;
	TRUNCATE TABLE person;
	TRUNCATE TABLE quiz_db;
   -- Rétablir les contraintes d'intégrité référentielle, pour garantir
   -- d'insérer des données cohérentes
	SET FOREIGN_KEY_CHECKS = 1;
	BEGIN
		DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			SHOW ERRORS;
			ROLLBACK;
		END;
		START TRANSACTION;
		INSERT INTO theme (theme_id, label) VALUES 
    (1, 'Requêtes simples'),
    (2, 'Restrictions'),
    (3, 'Jointures'),
    (4, 'Requêtes imbriquées 1 valeur'),
    (5, 'Requêtes imbriquées n valeurs'),
    (6, 'Having'),
    (7, 'Agrégats'),
    (8, 'Divers');
		
		INSERT INTO person (person_id, email, pwd, name, first_name, created_at, validated_at, is_trainer) VALUES 
    (1, 'etudiant1@gmail.com', 'azerty', 'Sherlock', 'Holmes', v_date - INTERVAL 3 DAY, v_date - INTERVAL 2 DAY - INTERVAL 2 HOUR, 0),
    (2, 'etudiant2@gmail.com', 'azerty', 'Obama', 'Barack', v_date - INTERVAL 2 DAY, v_date - INTERVAL 1 DAY - INTERVAL 2 HOUR, 0),
    (3, 'etudiant3@gmail.com', 'azerty', 'Curie', 'Marie', v_date - INTERVAL 1 DAY, v_date - INTERVAL 2 HOUR, 0),
    (4, 'etudiant4@gmail.com', 'azerty', 'Curie', 'Pierre', v_date - INTERVAL 5 DAY, v_date - INTERVAL 4 DAY - INTERVAL 9 HOUR, 0),
    (5, 'etudiant5@gmail.com', 'azerty', 'Moquet', 'Guy', v_date - INTERVAL 4 DAY, v_date - INTERVAL 3 DAY - INTERVAL 12 HOUR, 0),
    (6, 'formateur1@gmail.com', 'azerty', 'Apollinaire', 'Guillaume', v_date - INTERVAL 7 DAY, v_date - INTERVAL 6 DAY - INTERVAL 22 HOUR, 1),
    (7, 'formateur2@gmail.com', 'azerty', 'Zola', 'Emile',  v_date - INTERVAL 4 DAY, v_date - INTERVAL 3 DAY - INTERVAL 22 HOUR, 1),
    (8, 'formateur3@gmail.com', 'azerty', 'Bert', 'Paul',  v_date - INTERVAL 4 DAY, v_date - INTERVAL 3 DAY - INTERVAL 22 HOUR, 1),
    (9, 'michel.plasse@cefisi.com', 'azerty', 'Plasse', 'Michel',  v_date - INTERVAL 4 DAY, v_date - INTERVAL 3 DAY - INTERVAL 22 HOUR, 1);

		INSERT INTO usergroup (group_id, name, created_at, creator_id) VALUES 
    (1, 'Concepteur Développeur Informatique', now(), 6),
    -- (2, 'Tout le monde', now(), 6),
    (2, 'Assistant à Maîtrise d''Ouvrage', now(), 6),
    (3, 'Big Data Analyst', now(), 7);
				
		INSERT INTO group_member (person_id, group_id, validated_at) VALUES 
    (1, 1, v_date),
    (1, 3, v_date),
    (2, 1, NULL),
    (2, 2, v_date),
    (2, 3, v_date),
    (3, 1, v_date),
    (3, 3, v_date),
    (4, 3, v_date),
    (5, 3, v_date);
				
		INSERT INTO quiz_db ( db_name, diagram_path, creation_script_path, description) VALUES
    ('banque', 'banque.png', NULL, 'Ceci est un description exacte de la base de données banque'),
    ('avions', 'avions.png', NULL, 'Trust nobody not even yourself');

		INSERT INTO sql_quiz (quiz_id, author_id, title, is_public, db_name) VALUES 
    (1, 6, 'Quiz Banque', 1, 'banque'),
    (2, 7, 'Quiz Avion', 0, 'avions'),
    (3, 6, 'Quiz privé', 1, 'banque');
				
		INSERT INTO sql_question (question_id, db_name, question_text, correct_answer, theme_id, author_id, is_public, correct_result) VALUES 
    (1, 'banque', 'Nom et email de tous les clients', 'SELECT nom, email FROM client;', 1, 6, 0, '[Dupont, dupont@interpol.com]\n[Tintin, tintin@herge.be]\n[Haddock, haddock@moulinsart.fr]\n[Castafiore, bianca@scala.it]'),
    (2, 'banque', 'Dates d''attribution des clients aux commerciaux', 'SELECT date_attribution FROM portefeuille;', 1, 6, 0, '2005-12-23\n2010-04-21\n2015-04-12\n2015-04-12'),
    (3, 'banque', 'Date d''attribution sans doublon' ,'SELECT DISTINCT date_attribution FROM portefeuille;' ,1, 6, 1, '2005-12-23\n2010-04-21\n2015-04-12'),
    (4, 'banque', 'Longueur du mail des clients (fonction chaine)', 'SELECT email, length(email) FROM client;', 1, 6, 1, '[dupont@interpol.com, 19]\n[tintin@herge.be, 15]\n[haddock@moulinsart.fr, 21]\n[bianca@scala.it, 15]'),
    (5, 'banque', 'Nom du client suivi du commentaire entre parenthèses (utiliser IFNULL)', 'SELECT concat(nom, ifnull(concat('' ('', commentaire, '')''), '''')) FROM client', 1, 6, 1, 'Dupont (Client distrait. Je dirai même plus ...)\nTintin\nHaddock (Grand amateur de Loch Lhomond)\nCastafiore (A flatter. Ne surtout pas faire chanter)'),
    (6, 'banque', 'Solde minimal, moyen, maximal, et écart-type, arrondis à 2 décimales', 'SELECT MIN(solde) AS minimum, FORMAT(AVG(solde), 2) AS moyenne, MAX(solde) AS maximum, FORMAT(STDDEV(solde), 2) AS ecart_type FROM compte;', 7, 6, 1, '[300.00, 1,612.50, 4000.00, 1,221.49]'),
    (7, 'banque', 'Comptes avec n°, nom du client et solde', 'SELECT no_compte, nom AS nom_client, solde FROM compte INNER JOIN client ON compte.no_client = client.no_client;', 3, 6, 1, '[1, Dupont, 1250.00]\n[2, Dupont, 1250.00]\n[3, Tintin, 2590.00]\n[4, Haddock, 2500.00]\n[6, Dupont, 340.00]\n[7, Dupont, 4000.00]\n[8, Dupont, 300.00]\n[9, Dupont, 670.00]'),
    (8, 'banque', 'Comptes ayant un solde au moins égal au solde moyen (imbriquée 1 valeur)', 'SELECT * FROM compte WHERE solde >= (SELECT AVG(solde) FROM compte);', 4, 6, 1, '[3, 2590.00, 2]\n[4, 2500.00, 3]\n[7, 4000.00, 1]'),
    (9, 'avions', 'Date et heure des vols (année sur 4 positions, et heures sur 24 valeurs, pas sur 12)', 'SELECT date_depart, date_format(date_depart, ''%d-%m-%Y'') AS jour,	date_format(date_depart, ''%H:%i:%s'') AS heure FROM vol;', 1, 7, 0, ''),
    (10, 'avions', 'Date et heure des vols, avec le nombre de vols à cette date/heure (GROUP BY)', 'SELECT date_format(date_depart, ''%d-%m-%Y'') AS jour, date_format(date_depart, ''%H:%i:%s'') AS heure, COUNT(*) AS nb_vols FROM vol GROUP BY jour, heure;', 7, 7, 0, ''),
    (11, 'avions', 'Durée minimale, moyenne, maximale, et écart-type, arrondis à 2 décimales,des vols', 'SELECT MIN(TIMEDIFF(date_arrivee, date_depart)) AS minimum, TIME(AVG(TIMESTAMPDIFF(MINUTE, date_arrivee, date_depart))) AS moyenne, MAX(TIMEDIFF(date_arrivee, date_depart)) AS maximum, TIME(STDDEV(TIMESTAMPDIFF(MINUTE, date_arrivee, date_depart))) AS ecart_type FROM vol;', 7, 7, 1, ''),
    (12, 'avions', 'Avions au départ de ORY ou CDG (imbriquée n valeurs)', 'SELECT * FROM avion WHERE id_avion IN (SELECT id_avion FROM vol WHERE id_aeroport_depart IN (''CDG'', ''ORY''));', 4, 7, 1, ''),
    (13, 'avions', 'Nombre d''heures de vol par pilote, avec id et nom (SUM et GROUP BY)', 'SELECT p.id_pilote, nom, ROUND(SUM(TIMESTAMPDIFF(MINUTE, date_depart, date_arrivee))/60) AS nb_heures FROM pilote p INNER JOIN vol ON p.id_pilote = vol.id_pilote GROUP BY p.id_pilote, nom;', 7, 7, 1, ''),
    (14, 'avions', 'Nom et n° des pilotes ayant piloté au moins 2 avions (DISTINCT et HAVING)', 'SELECT p.id_pilote, nom, COUNT(DISTINCT id_avion) AS nb_avions FROM pilote p INNER JOIN vol ON p.id_pilote = vol.id_pilote GROUP BY p.id_pilote, nom HAVING nb_avions >= 2;', 6, 7, 1, ''),
    (15, 'avions', 'Pilotes sans vol (avec HAVING)', 'SELECT p.id_pilote, nom, COUNT(id_avion) AS nb_vols FROM pilote p LEFT OUTER JOIN vol v ON p.id_pilote = v.id_pilote GROUP BY p.id_pilote, nom HAVING nb_vols = 0;', 6, 7, 1, ''),
    (16, 'avions', 'Avions volant le 12/04/2015 (imbriquée n valeurs)', 'SELECT * FROM avion WHERE id_avion IN (SELECT id_avion FROM vol WHERE date(date_depart) = ''2015-04-12'');', 5, 7, 1, '');
				
    INSERT INTO sql_quiz_question(question_id, quiz_id, rank) VALUES 
    (1, 1, 1),
    (2, 1, 2),
    (3, 1, 3),
    (4, 1, 4),
    (5, 1, 5),
    (6, 1, 6),
    (7, 1, 7),
    (8, 1, 8),
    (9, 2, 1),
    (10, 2, 2),
    (11, 2, 3),
    (12, 2, 4),
    (13, 2, 5),
    (14, 2, 6),
    (15, 2, 7),
    (16, 2, 8);
				
    INSERT INTO evaluation (evaluation_id, group_id, scheduled_at, ending_at, 
      precorrected_at, corrected_at, quiz_id) VALUES 
    -- corrected
    (1, 1, v_date - INTERVAL 4 DAY, v_date - INTERVAL 4 DAY + INTERVAL 1 HOUR, 
      v_date - INTERVAL 1 DAY, v_date - INTERVAL 1 DAY, 1),
    -- finished, may be precorrected
    (2, 1, v_date - INTERVAL 3 DAY + INTERVAL 1 HOUR, v_date - INTERVAL 3 DAY + INTERVAL 2 HOUR, 
      NULL, NULL, 1),
    -- finished
    (3, 1, v_date - INTERVAL 2 DAY, v_date - INTERVAL 2 DAY + INTERVAL 2 HOUR, 
      NULL, NULL, 2),
    -- ongoing during 10 minutes
    (4, 1, v_date, v_date + INTERVAL 1 MINUTE, NULL, NULL, 1),
    -- ongoing during 8 hours
    (5, 1, v_date - INTERVAL 1 HOUR, v_date + INTERVAL 7 HOUR, NULL, NULL, 2),
    -- future
    (6, 1, v_date + INTERVAL 3 DAY, v_date + INTERVAL 3 DAY + INTERVAL 1 HOUR, NULL, NULL, 2),
    -- group 2, ongoing during 8 hours
    (7, 2, v_date - INTERVAL 1 HOUR, v_date + INTERVAL 7 HOUR, NULL, NULL, 2),
    -- group 3, ongoing during 8 hours
    (8, 3, v_date - INTERVAL 1 HOUR, v_date + INTERVAL 7 HOUR, NULL, NULL, 2);

    -- sheet and sheet_answer are populated by trigger when inserting an evaluation
    -- Set two answers in evaluation 2 in order to precorrect
    UPDATE sheet_answer
    SET answer = 'SELECT nom, email FROM client;' -- correct
    WHERE question_id=1 AND trainee_id=1 AND evaluation_id=2;
    UPDATE sheet_answer
    SET answer='SELECT email, nom FROM client;' -- reverse
    WHERE question_id=1 AND trainee_id=3 AND evaluation_id=2;
    UPDATE sheet_answer
    SET answer='SELECT date_attribution FROM portefeuille;' -- wrong
    WHERE question_id=3 AND trainee_id=1 AND evaluation_id=2;
    UPDATE sheet_answer
    SET answer='SELECT * FROM compte WHERE solde >= (SELECT AVG(solde) FROM compte);' -- ok
    WHERE question_id=8 AND trainee_id=1 AND evaluation_id=2;

--     INSERT INTO training (training_id, quiz_id, trainee_id, started_at,ended_at) VALUES
-- 		(1, 1, 1, v_date - INTERVAL 3 DAY, NULL),
--     (2, 2, 2, v_date - INTERVAL 5 DAY, NULL),
--     (3, 1, 3, v_date - INTERVAL 7 DAY, v_date),
--     (4, 1, 4, v_date - INTERVAL 1 DAY, v_date),
--     (5, 2, 1, v_date,null);
			
		COMMIT;
	END;
END$$



CREATE TRIGGER evaluation_after_insert
AFTER INSERT ON evaluation
FOR EACH ROW
BEGIN
  DECLARE v_person_id INT;
  DECLARE termine BOOLEAN DEFAULT FALSE;
  DECLARE person_list CURSOR FOR 
    SELECT person_id 
    FROM group_member 
    WHERE group_id = NEW.group_id and validated_at IS NOT NULL;
  OPEN person_list;
  BEGIN
    DECLARE EXIT HANDLER FOR NOT FOUND SET termine = TRUE;
    REPEAT
      FETCH person_list INTO v_person_id;
      INSERT INTO sheet(trainee_id, evaluation_id)	
      VALUES (v_person_id, NEW.evaluation_id);
    UNTIL termine END REPEAT;
  END;
  CLOSE person_list;
END$$


CREATE TRIGGER sheet_after_insert
AFTER INSERT ON sheet
FOR EACH ROW
BEGIN
  INSERT INTO sheet_answer(question_id, trainee_id, evaluation_id)
  SELECT question_id, NEW.trainee_id, NEW.evaluation_id
  FROM sql_quiz_question
  WHERE quiz_id IN
  (
    SELECT quiz_id
    FROM evaluation
    WHERE evaluation_id = NEW.evaluation_id
  ); 
END$$


CALL sql_skills_reset()$$

/* Create a user to be used in PHP for the connection,
 * and give him all grants on the DB.
 */
-- Delete the user ...
DELETE FROM mysql.user WHERE user='skillsEpita2020' $$
-- and his grants
DELETE FROM mysql.db WHERE user='skillsEpita2020' $$
DELETE FROM mysql.tables_priv WHERE user='skillsEpita2020' $$
FLUSH PRIVILEGES $$
-- Create him
CREATE USER skillsEpita2020@localhost IDENTIFIED by 'passwordEpita2020' $$
-- Grant him rights on the DB ...
GRANT ALL ON sql_skills_fall2019.* TO skillsEpita2020@localhost $$
-- Grant select privileges on target dbs
--GRANT SELECT ON banque TO skillsEpita2020@localhost $$
--GRANT SELECT ON avions TO skillsEpita2020@localhost $$
-- and on the stored procedure
GRANT SELECT ON mysql.proc TO skillsEpita2020@localhost $$
