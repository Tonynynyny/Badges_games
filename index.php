<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/fonctions.php';

// TODO (Personne 5) : Traiter le formulaire de création d'équipe et rediriger vers jeu.php

require_once __DIR__ . '/includes/haut.php';
?>

<!-- TODO (Personne 5) : Formulaire de saisie du nom de l'équipe pour lancer l'épreuve -->
<h1>Escape Game - Épreuve des Badges</h1>

<?php require_once __DIR__ . '/includes/bas.php'; ?>