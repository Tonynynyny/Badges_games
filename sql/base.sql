-- ============================================================================
-- FICHIER : sql/base.sql
-- RESPONSABLE : Personne 1 (Architecte Base de Données)
-- DESCRIPTION : Création de la structure de la base de données et insertion
--               des badges par défaut pour l'épreuve de l'Escape Game.
-- ============================================================================

-- TODO 1 : Créer la base de données (ex: badges_game) si elle n'existe pas et la sélectionner.


-- TODO 2 : Créer la table `badges` avec exactement ces colonnes :
--   - id          : INT, clé primaire, auto-incrémenté
--   - code_badge  : VARCHAR(50), unique, non nul (ex: 'BADGE_ROUGE' maintenant, UID RFID plus tard)
--   - nom         : VARCHAR(50), non nul (ex: 'Badge Rouge')
--   - couleur     : VARCHAR(20), non nul (ex: '#FF0000' ou 'red' pour l'affichage CSS)


-- TODO 3 : Créer la table `parties` avec exactement ces colonnes :
--   - id                   : INT, clé primaire, auto-incrémenté
--   - nom_equipe           : VARCHAR(100), non nul
--   - combinaison_secrete  : VARCHAR(255), non nul (ex: 'BADGE_ROUGE,BADGE_BLEU,BADGE_VERT,BADGE_JAUNE')
--   - statut               : ENUM('en_cours', 'gagne', 'abandon') DEFAULT 'en_cours'
--   - date_debut           : DATETIME DEFAULT CURRENT_TIMESTAMP
--   - date_fin             : DATETIME NULL
--   - temps_secondes       : INT NULL (durée totale de la partie une fois gagnée)


-- TODO 4 : Créer la table `tentatives` avec exactement ces colonnes :
--   - id                    : INT, clé primaire, auto-incrémenté
--   - id_partie             : INT, non nul (Clé étrangère vers parties.id avec ON DELETE CASCADE)
--   - combinaison_proposee  : VARCHAR(255), non nul (ex: 'BADGE_BLEU,BADGE_ROUGE,BADGE_VERT,BADGE_JAUNE')
--   - nb_corrects           : INT, non nul (nombre de badges bien placés lors de cet essai)
--   - date_tentative        : DATETIME DEFAULT CURRENT_TIMESTAMP


-- TODO 5 : Insérer un jeu de données initial de 4 à 6 badges dans la table `badges`
-- Exemple de codes attendus : 'BADGE_ROUGE', 'BADGE_BLEU', 'BADGE_VERT', 'BADGE_JAUNE'