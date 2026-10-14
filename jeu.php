<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/logique.php';
require_once __DIR__ . '/includes/fonctions.php';

// TODO (Personne 4) : Vérifier qu'une partie est en cours en session et traiter la combinaison soumise

require_once __DIR__ . '/includes/haut.php';
?>

<!-- TODO (Personne 4) : Interface de sélection de l'ordre des badges et historique des essais -->
<h1>Interface de décryptage des badges</h1>

<?php require_once __DIR__ . '/includes/bas.php'; ?>