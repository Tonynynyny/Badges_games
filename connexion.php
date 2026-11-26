<?php
// Type de connexion choisi : joueur ou administrateur.
$type = $_GET['type'] ?? null;

// Message d'erreur éventuellement transmis après une tentative de connexion.
$erreur = $_GET['erreur'] ?? '';

// On n'accepte que les deux types de connexion prévus.
if (!in_array($type, ['joueur', 'admin'], true)) {
    $type = null;
}

$titre = 'Connexion';
require_once __DIR__ . '/includes/haut.php';
?>

<h1>Bienvenue sur l'Escape Game</h1>

<!-- Choix du type de connexion -->
<form method="GET" action="connexion.php" class="actions">
    <button type="submit" name="type" value="joueur"
            class="bouton <?= $type === 'joueur' ? '' : 'bouton-secondaire' ?>">
        Un joueur
    </button>

    <button type="submit" name="type" value="admin"
            class="bouton <?= $type === 'admin' ? '' : 'bouton-secondaire' ?>">
        Un animateur
    </button>
</form>

<!-- Formulaire de connexion joueur -->
<?php if ($type === 'joueur'): ?>

    <div class="carte">
        <h2>Rejoindre une partie en cours</h2>

        <?php if ($erreur === 'equipe'): ?>
            <div class="message message-erreur" role="alert">
                Équipe introuvable ou partie déjà terminée.
            </div>
        <?php endif; ?>

        <form action="connexion_joueur.php" method="POST">
            <label for="id_equipe">Numéro de votre équipe :</label>
            <input
                type="number"
                id="id_equipe"
                name="id_equipe"
                min="1"
                required
                autofocus
            >

            <div>
                <button type="submit" class="bouton">Rejoindre la partie</button>
            </div>
        </form>

        <p class="texte-doux">Pas encore d'équipe ? <a href="index.php">Créez-la ici</a>.</p>
    </div>

<!-- Formulaire de connexion administrateur -->
<?php elseif ($type === 'admin'): ?>

    <div class="carte">
        <h2>Connexion animateur</h2>

        <?php if ($erreur === 'identifiants'): ?>
            <div class="message message-erreur" role="alert">
                Identifiant ou mot de passe incorrect.
            </div>
        <?php endif; ?>

        <form action="connexion_admin.php" method="POST">
            <label for="identifiant">Identifiant :</label>
            <input
                type="text"
                id="identifiant"
                name="identifiant"
                autocomplete="username"
                required
                autofocus
            >

            <label for="mdp">Mot de passe :</label>
            <input
                type="password"
                id="mdp"
                name="mdp"
                autocomplete="current-password"
                required
            >

            <div>
                <button type="submit" class="bouton">Se connecter</button>
            </div>
        </form>
    </div>

<?php endif; ?>

<?php require_once __DIR__ . '/includes/bas.php'; ?>
