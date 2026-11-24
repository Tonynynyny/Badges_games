-- ============================================================================
-- FICHIER : sql/base.sql
-- RESPONSABLE : Personne 1 (Architecte Base de Données)
-- DESCRIPTION : Création de la structure de la base de données et insertion
--               des badges par défaut pour l'épreuve de l'Escape Game.
-- ============================================================================

-- TODO 1 : Base de données
CREATE DATABASE IF NOT EXISTS badges_game CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE badges_game;

-- TODO 2 : Table badges
-- Pas de couleur : c'est l'apparence du badge physique, pas du code.
-- code_badge : 1, 2, 3, 4 maintenant
CREATE TABLE IF NOT EXISTS badges (
  id INT NOT NULL AUTO_INCREMENT,
  code_badge VARCHAR(50) NOT NULL UNIQUE,
  nom VARCHAR(50) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

-- TODO 3 : Table parties
-- combinaison_secrete : générée côté serveur, une seule fois.
-- temps_secondes : date_fin - date_debut, calculé à la victoire.
CREATE TABLE IF NOT EXISTS parties (
  id INT NOT NULL AUTO_INCREMENT,
  nom_equipe VARCHAR(100) NOT NULL,
  combinaison_secrete VARCHAR(255) NOT NULL,
  statut ENUM('en_cours', 'gagne', 'abandon') NOT NULL DEFAULT 'en_cours',
  date_debut DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_fin DATETIME NULL,
  temps_secondes INT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

-- TODO 4 : Table tentatives
CREATE TABLE IF NOT EXISTS tentatives (
  id INT NOT NULL AUTO_INCREMENT,
  id_partie INT NOT NULL,
  combinaison_proposee VARCHAR(255) NOT NULL,
  nb_corrects INT NOT NULL,
  date_tentative DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (id_partie) REFERENCES parties(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- TODO 5 : Badges par défaut (la couleur reste sur le badge physique)
INSERT INTO badges (code_badge, nom) VALUES
  ('1', 'Badge 1'),
  ('2', 'Badge 2'),
  ('3', 'Badge 3'),
  ('4', 'Badge 4');

CREATE TABLE IF NOT EXISTS administrateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    identifiant VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);
