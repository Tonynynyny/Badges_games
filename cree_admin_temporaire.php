<?php

require_once __DIR__ . '/includes/fonctions.php';

$identifiant = 'animateur';
$motDePasse = 'ChangeCeMotDePasse123!';

// Création du hash sécurisé
$hash = password_hash($motDePasse, PASSWORD_DEFAULT);

// Insertion dans la BDD
$sql = "INSERT INTO administrateurs (identifiant, mot_de_passe)
        VALUES (:identifiant, :mot_de_passe)";

$requete = $pdo->prepare($sql);

$requete->execute([
    'identifiant' => $identifiant,
    'mot_de_passe' => $hash
]);

echo "Administrateur créé avec succès !";
