SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Supprimez les tables s'il elles existent déjà
DROP TABLE IF EXISTS Soumettre;
DROP TABLE IF EXISTS Contribution;
DROP TABLE IF EXISTS Cadavre;
DROP TABLE IF EXISTS Administrateur;
DROP TABLE IF EXISTS Joueur;

-- Créez les nouvelles tables
CREATE TABLE Joueur (
   id_joueur INT AUTO_INCREMENT,
   nom_plume VARCHAR(50) NOT NULL,
   ad_mail_joueur VARCHAR(50) NOT NULL,
   sexe VARCHAR(50) NOT NULL,
   ddn DATE NOT NULL,
   mot_de_passe_joueur VARCHAR(50) NOT NULL,
   PRIMARY KEY (id_joueur),
   UNIQUE (nom_plume)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE Administrateur (
   id_administrateur INT AUTO_INCREMENT,
   ad_mail_administrateur VARCHAR(50) NOT NULL,
   mot_de_passe_administrateur VARCHAR(50) NOT NULL,
   PRIMARY KEY (id_administrateur),
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE Cadavre (
   id_cadavre INT AUTO_INCREMENT,
   titre_cadavre VARCHAR(100) NOT NULL,
   date_debut_cadavre DATETIME NOT NULL,
   date_fin_cadavre DATETIME NOT NULL,
   nb_contributions INT NOT NULL,
   nb_jaime INT NOT NULL,
   id_administrateur INT NOT NULL,
   PRIMARY KEY (id_cadavre),
   FOREIGN KEY (id_administrateur) REFERENCES Administrateur(id_administrateur)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE Contribution (
    id_contribution INT AUTO_INCREMENT PRIMARY KEY,
    texte_contribution TEXT NOT NULL,
    date_soumission DATETIME NOT NULL,
    ordre_soumission INT NOT NULL,
    id_administrateur INT,
    id_cadavre INT NOT NULL,
    FOREIGN KEY (id_administrateur) REFERENCES Administrateur(id_administrateur),
    FOREIGN KEY (id_cadavre) REFERENCES Cadavre(id_cadavre)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE Soumettre (
   id_joueur INT,
   id_cadavre INT,
   num_contribution INT NOT NULL,
   PRIMARY KEY (id_joueur, id_cadavre),
   FOREIGN KEY (id_joueur) REFERENCES Joueur(id_joueur),
   FOREIGN KEY (id_cadavre) REFERENCES Cadavre(id_cadavre)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--Triggers pour gérer nombre maximal de contrib et aussi la période de contribution -- 

CREATE TRIGGER avant_insertion_contribution
BEFORE INSERT ON Contribution
FOR EACH ROW
BEGIN
    DECLARE current_contributions INT;
    DECLARE max_contributions INT;
    DECLARE end_date DATETIME;
    
    SELECT COUNT(*) INTO current_contributions FROM Contribution WHERE id_cadavre = NEW.id_cadavre;
    SELECT nb_contributions INTO max_contributions FROM Cadavre WHERE id = NEW.id_cadavre;
    SELECT 	date_fin_cadavre INTO end_date FROM Cadavre WHERE id = NEW.id_cadavre;
    
    IF current_contributions >= max_contributions THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le nombre maximal de contributions pour ce cadavre a été atteint';
    END IF;
    
    IF NOW() > end_date THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La période de dépôt des contributions est terminée';
    END IF;
END;

-- Trigger longueur de texte ---


CREATE TRIGGER longueurtexte_insertion_contribution
BEFORE INSERT ON Contribution
FOR EACH ROW
BEGIN
  IF LENGTH(NEW.texte_contribution) < 50 OR LENGTH(NEW.texte_contribution) > 280 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'La longueur de texte_contribution doit être entre 50 et 280 caractères';
  END IF;
END;
 ;
