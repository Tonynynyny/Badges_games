<?php

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/fonctions.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Vérification de l'identifiant reçu
$idEquipe = filter_input(INPUT_POST, 'id_equipe', FILTER_VALIDATE_INT);

if (!$idEquipe || $idEquipe <= 0) {
    header('Location: index.php?type=joueur&erreur=equipe');
    exit;
}

// Recherche de l'équipe dans la BDD
$partie = recupererPartie($pdo, $idEquipe);

// Vérification de l'existence et du statut de la partie
if ($partie === null || !in_array($partie['statut'], ['en_attente', 'en_cours'], true)) {
    header('Location: index.php?type=joueur&erreur=equipe');
    exit;
}

// Première connexion de l'équipe : le chrono démarre maintenant
if ($partie['statut'] === 'en_attente') {
    demarrerPartie($pdo, (int) $partie['id']);
}

// Connexion du joueur (on repart d'une session propre)
session_regenerate_id(true);

$_SESSION = [
    'role'       => 'joueur',
    'id_partie'  => (int) $partie['id'],
    'nom_equipe' => $partie['nom_equipe'],
];

// Redirection vers le jeu
header('Location: jeu.php');
exit;
