<?php
// Chaque page définit $titre AVANT d'inclure ce fichier (ex : $titre = 'Classement';)
$titrePage = isset($titre) ? $titre . ' — Badges Game' : 'Badges Game';

// Nom du fichier en cours (ex : "classement.php"), pour surligner le bon lien du menu
$pageCourante = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titrePage) ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="entete">
        <a href="index.php" class="logo">Badges Game</a>
        <nav class="menu">
            <a href="index.php"
               class="<?= in_array($pageCourante, ['index.php', 'jeu.php']) ? 'actif' : '' ?>">Jouer</a>
            <a href="classement.php"
               class="<?= $pageCourante === 'classement.php' ? 'actif' : '' ?>">Classement</a>
        </nav>
    </header>
    <main class="contenu">
