<?php
/* =========================================================
 *  Configuration du Jeu des Badges
 *  À adapter selon votre installation (XAMPP, WAMP, Laragon...)
 * ========================================================= */

// --- Connexion MySQL ---
define('DB_HOST', '127.0.0.1');
define('DB_PORT', 3306);
define('DB_NAME', 'badges_game');
define('DB_USER', 'root');
define('DB_PASS', '');            // XAMPP / WAMP : vide par défaut / MAMP : 'root'

// --- Règles du jeu ---
// Nombre de badges dans l'ordre secret
define('TAILLE_COMBINAISON', 4);

// Nombre maximum de tentatives par partie (0 = illimité)
define('MAX_TENTATIVES', 10);

// --- Espace animateur ---
// Les comptes animateur sont stockés dans la table `administrateurs`.
// Compte créé par sql/base.sql : identifiant « animateur »,
// mot de passe « ChangeCeMotDePasse123! » (à changer avant l'intervention,
// avec cree_admin_temporaire.php).
define('ADMIN_PASSWORD', '');

// --- Affichage des erreurs ---
// false : les joueurs voient une page « Oups ! » simple (à garder pendant l'intervention).
// true  : affiche aussi le message technique (pratique pour dépanner).
define('AFFICHER_ERREURS', false);
