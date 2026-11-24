
<?php
$type = $_GET['type'] ?? null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>

<body>

    <h1>Bienvenue sur l'Escape Game</h1>

    <!-- Choix du type de connexion -->
    <form method="GET">
        <button type="submit" name="type" value="joueur">
            Un joueur
        </button>

        <button type="submit" name="type" value="admin">
            Un admin
        </button>
    </form>

    <!-- Formulaire joueur -->
    <?php if ($type === 'joueur'): ?>

        <h2>Connexion joueur</h2>

        <form action="connexion_joueur.php" method="POST">

            <label for="id_equipe">Identifiant de votre équipe :</label>

            <input
                type="number"
                id="id_equipe"
                name="id_equipe"
                required
                min="1"
            >

            <button type="submit">Rejoindre la partie</button>

        </form>

    <!-- Formulaire admin -->
    <?php elseif ($type === 'admin'): ?>

        <h2>Connexion administrateur</h2>

        <form action="connexion_admin.php" method="POST">

            <label for="identifiant">Identifiant :</label>
            <input type="text" id="identifiant" name="identifiant" required>

            <label for="mdp">Mot de passe :</label>
            <input type="password" id="mdp" name="mdp" required>

            <button type="submit">Se connecter</button>

        </form>

    <?php endif; ?>

</body>
</html>
