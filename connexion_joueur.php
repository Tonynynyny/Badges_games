<?php

require_once __DIR__ . '/includes/fonctions.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Vérification de l'identifiant reçu
$idEquipe = filter_input(INPUT_POST, 'id_equipe', FILTER_VALIDATE_INT);

if (!$idEquipe || $idEquipe <= 0) {
    header('Location: connexion.php?type=joueur&erreur=equipe');
    exit;
}

// Recherche de l'équipe dans la BDD
$partie = recupererPartie($pdo, $idEquipe);

// Vérification de l'existence et du statut de la partie
if ($partie === null || $partie['statut'] !== 'en_cours') {
    header('Location: connexion.php?type=joueur&erreur=equipe');
    exit;
}

// Connexion du joueur
session_regenerate_id(true);

$_SESSION['role'] = 'joueur';
$_SESSION['id_partie'] = (int) $partie['id'];
$_SESSION['nom_equipe'] = $partie['nom_equipe'];

// Redirection vers le jeu
header('Location: jeu.php');
exit;
