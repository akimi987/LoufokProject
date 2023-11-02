--
-- Base de données : `loufok`
--

-- --------------------------------------------------------

CREATE TABLE Joueur(
   id_joueur INT,
   nom_plume VARCHAR(50) NOT NULL,
   ad_mail_joueur VARCHAR(50) NOT NULL,
   sexe VARCHAR(50) NOT NULL,
   ddn DATE NOT NULL,
   mot_de_passe_joueur VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_joueur),
   UNIQUE(nom_plume)
);
ALTER TABLE `Joueur`
  ADD PRIMARY KEY (`id_joueur`);

CREATE TABLE Administrateur(
   id_administrateur INT,
   ad_mail_administrateur VARCHAR(50) NOT NULL,
   mot_de_passe_administrateur VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_administrateur)
);
ALTER TABLE `id_administrateur`
ADD PRIMARY KEY (`id`);

CREATE TABLE Cadavre(
   id_cadavre INT,
   titre_cadavre VARCHAR(100) NOT NULL,
   date_debut_cadavre DATETIME NOT NULL,
   date_fin_cadavre DATETIME NOT NULL,
   nb_contributions INT NOT NULL,
   nb_jaime INT NOT NULL,
   id_administrateur INT NOT NULL,
   PRIMARY KEY(id_cadavre),
   FOREIGN KEY(id_administrateur) REFERENCES Administrateur(id_administrateur)
);
ALTER TABLE `Cadavre`
ADD PRIMARY KEY (`id_cadavre`);
ADD KEY `id_administrateur` (`id_administrateur`);


CREATE TABLE Contribution(
   id_contribution VARCHAR(50),
   texte_contribution VARCHAR(280) NOT NULL,
   date_soumission DATETIME NOT NULL,
   ordre_soumission VARCHAR(50) NOT NULL,
   id_administrateur INT,
   id_cadavre INT NOT NULL,
   PRIMARY KEY(id_contribution),
   FOREIGN KEY(id_administrateur) REFERENCES Administrateur(id_administrateur),
   FOREIGN KEY(id_cadavre) REFERENCES Cadavre(id_cadavre)
);
ALTER TABLE `Contribution`
ADD PRIMARY KEY (`id_contribution`);
ADD KEY `id_cadavre` (`id_cadavre`);
ADD KEY `id_administrateur` (`id_administrateur`);

CREATE TABLE Soumettre(
   id_joueur INT,
   id_cadavre INT,
   num_contribution INT NOT NULL,
   PRIMARY KEY(id_joueur, id_cadavre),
   FOREIGN KEY(id_joueur) REFERENCES Joueur(id_joueur),
   FOREIGN KEY(id_cadavre) REFERENCES Cadavre(id_cadavre)
);

  ALTER TABLE `Soumettre`
  ADD PRIMARY KEY (`id_joueur`,`id_cadavre`),
  ADD KEY `id_cadavre` (`id_cadavre`);
  ADD KEY `id_joueur` (`id_joueur`);

