
<?php

require_once __DIR__ . '/includes/fonctions.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$identifiant = trim($_POST['identifiant'] ?? '');
$motDePasse = $_POST['mdp'] ?? '';

// Vérification des champs
if ($identifiant === '' || $motDePasse === '') {
    header('Location: connexion.php?type=admin&erreur=identifiants');
    exit;
}

// Recherche de l'administrateur dans la BDD
$admin = recupererAdminParIdentifiant($pdo, $identifiant);

// Vérification du mot de passe
if (
    $admin === null ||
    !password_verify($motDePasse, $admin['mot_de_passe'])
) {
    header('Location: connexion.php?type=admin&erreur=identifiants');
    exit;
}

// Connexion réussie
session_regenerate_id(true);

$_SESSION['role'] = 'admin';
$_SESSION['id_admin'] = (int) $admin['id'];
$_SESSION['identifiant_admin'] = $admin['identifiant'];

// Redirection vers l'administration
header('Location: admin.php');
exit;
