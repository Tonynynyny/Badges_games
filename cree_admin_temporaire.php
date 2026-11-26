<?php
/* =========================================================
 *  Création d'un compte animateur, ou changement de son mot de passe.
 *
 *  - Dans le navigateur : http://localhost/.../cree_admin_temporaire.php
 *    (accessible UNIQUEMENT depuis l'ordinateur qui héberge le site)
 *  - En ligne de commande : php cree_admin_temporaire.php animateur NouveauMotDePasse
 *
 *  Conseil : supprimez ce fichier une fois le compte créé.
 * ========================================================= */
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/fonctions.php';

// ---------- Ligne de commande ----------
if (PHP_SAPI === 'cli') {
    $identifiant = $argv[1] ?? '';
    $motDePasse  = $argv[2] ?? '';

    if ($identifiant === '' || strlen($motDePasse) < 8) {
        fwrite(STDERR, "Usage : php cree_admin_temporaire.php <identifiant> <mot de passe (8 caractères min.)>\n");
        exit(1);
    }

    enregistrerAdmin($pdo, $identifiant, $motDePasse);
    echo "Compte animateur « $identifiant » enregistré.\n";
    exit(0);
}

// ---------- Navigateur : uniquement en local ----------
$ip = $_SERVER['REMOTE_ADDR'] ?? '';
if (!in_array($ip, ['127.0.0.1', '::1'], true)) {
    http_response_code(403);
    exit('Accès refusé : ouvrez cette page depuis l\'ordinateur qui héberge le site (localhost).');
}

$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = trim($_POST['identifiant'] ?? '');
    $motDePasse  = $_POST['mdp'] ?? '';

    if ($identifiant === '' || strlen($motDePasse) < 8) {
        $message = ['erreur', 'Identifiant obligatoire et mot de passe de 8 caractères minimum.'];
    } else {
        enregistrerAdmin($pdo, $identifiant, $motDePasse);
        $message = ['succes', "Compte animateur « $identifiant » enregistré. Vous pouvez supprimer ce fichier."];
    }
}

$titre = 'Compte animateur';
require __DIR__ . '/includes/haut.php';
?>

<h1>Créer / modifier un compte animateur</h1>

<?php if ($message !== null): ?>
    <div class="message <?= $message[0] === 'erreur' ? 'message-erreur' : 'message-succes' ?>">
        <?= htmlspecialchars($message[1]) ?>
    </div>
<?php endif; ?>

<div class="carte">
    <form method="POST" action="cree_admin_temporaire.php">
        <label for="identifiant">Identifiant</label>
        <input type="text" id="identifiant" name="identifiant" value="animateur" required>

        <label for="mdp">Nouveau mot de passe (8 caractères min.)</label>
        <input type="password" id="mdp" name="mdp" minlength="8" required autocomplete="new-password">

        <div>
            <button type="submit" class="bouton">Enregistrer</button>
        </div>
    </form>
</div>

<?php require __DIR__ . '/includes/bas.php'; ?>
