<?php
// Type de connexion choisi : joueur ou administrateur.
$type = $_GET['type'] ?? null;

// Message d'erreur éventuellement transmis après une tentative de connexion.
$erreur = $_GET['erreur'] ?? '';

// On n'accepte que les deux types de connexion prévus.
if (!in_array($type, ['joueur', 'admin'], true)) {
    $type = null;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Escape Game</title>
</head>
<body>

    <h1>Bienvenue sur l'Escape Game</h1>

    <!-- Choix du type de connexion -->
    <form method="GET" action="connexion.php">
        <button type="submit" name="type" value="joueur">
            Un joueur
        </button>

        <button type="submit" name="type" value="admin">
            Un admin
        </button>
    </form>

    <!-- Formulaire de connexion joueur -->
    <?php if ($type === 'joueur'): ?>

        <h2>Connexion joueur</h2>

        <form action="connexion_joueur.php" method="POST">
            <label for="id_equipe">Identifiant de votre équipe :</label>
            <input
                type="number"
                id="id_equipe"
                name="id_equipe"
                min="1"
                required
            >

            <button type="submit">Rejoindre la partie</button>
        </form>

        <?php if ($erreur === 'equipe'): ?>
            <p style="color: red;" role="alert">
                Équipe introuvable ou partie non disponible.
            </p>
        <?php endif; ?>

    <!-- Formulaire de connexion administrateur -->
    <?php elseif ($type === 'admin'): ?>

        <h2>Connexion administrateur</h2>

        <form action="connexion_admin.php" method="POST">
            <label for="identifiant">Identifiant :</label>
            <input
                type="text"
                id="identifiant"
                name="identifiant"
                autocomplete="username"
                required
            >

            <label for="mdp">Mot de passe :</label>
            <input
                type="password"
                id="mdp"
                name="mdp"
                autocomplete="current-password"
                required
            >

            <button type="submit">Se connecter</button>
        </form>

        <?php if ($erreur === 'identifiants'): ?>
            <p style="color: red;" role="alert">
                Identifiant ou mot de passe incorrect.
            </p>
        <?php endif; ?>

    <?php endif; ?>

</body>
</html>
