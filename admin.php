<?php
/* =========================================================
 *  Espace animateur : statistiques, parties récentes
 *  (avec le code secret, pour aider un joueur bloqué)
 *  et remise à zéro entre deux sessions.
 * ========================================================= */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/fonctions.php';

verifierAdmin();

// ---------- Actions (POST → redirection → GET) ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if (!verifierJetonCsrf($_POST['csrf'] ?? null)) {
        $_SESSION['flash'] = ['erreur', 'Session expirée, veuillez réessayer.'];
    } elseif ($action === 'creer_equipe') {
        $nomEquipe = trim($_POST['nom_equipe'] ?? '');
        $longueur  = mb_strlen($nomEquipe);

        if ($longueur < 2 || $longueur > 100) {
            $_SESSION['flash'] = ['erreur', "Le nom d'équipe doit contenir entre 2 et 100 caractères."];
        } else {
            try {
                $secret   = genererCombinaisonSecrete(recupererCodesBadges($pdo), TAILLE_COMBINAISON);
                $idPartie = creerNouvellePartie($pdo, $nomEquipe, $secret);
                $_SESSION['flash'] = ['succes', "Équipe « $nomEquipe » créée : numéro de connexion #$idPartie"];
            } catch (InvalidArgumentException $e) {
                $_SESSION['flash'] = ['erreur', 'Pas assez de badges en base : relancez sql/base.sql.'];
            }
        }
    } elseif ($action === 'reinitialiser') {
        $_SESSION['flash'] = reinitialiserToutesLesParties($pdo)
            ? ['succes', 'Toutes les parties ont été supprimées.']
            : ['erreur', 'La remise à zéro a échoué.'];
    } elseif ($action === 'terminer') {
        $idPartie = (int) ($_POST['id_partie'] ?? 0);
        $_SESSION['flash'] = declarerAbandonPartie($pdo, $idPartie)
            ? ['succes', "La partie #$idPartie a été terminée."]
            : ['erreur', "La partie #$idPartie n'était pas en cours."];
    }

    header('Location: admin.php');
    exit;
}

$parties = recupererToutesLesParties($pdo);
$flash   = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// ---------- Statistiques ----------
$nbParties = count($parties);
$nbAttente = count(array_filter($parties, fn($p) => $p['statut'] === 'en_attente'));
$nbEnCours = count(array_filter($parties, fn($p) => $p['statut'] === 'en_cours'));
$gagnees   = array_filter($parties, fn($p) => $p['statut'] === 'gagne');
$nbGagnees = count($gagnees);
$meilleurTemps = $gagnees ? min(array_map(fn($p) => (int) $p['temps_secondes'], $gagnees)) : null;

$libellesStatut = [
    'en_attente' => 'En attente',
    'en_cours'   => 'En cours',
    'gagne'    => 'Gagnée',
    'abandon'  => 'Terminée (perdue)',
];

$titre = 'Espace animateur';
require __DIR__ . '/includes/haut.php';
?>

<h1>Espace animateur</h1>
<p class="texte-doux">
    Connecté en tant que <strong><?= htmlspecialchars($_SESSION['identifiant_admin'] ?? '') ?></strong>.
</p>

<?php if ($flash !== null): ?>
    <div class="message <?= $flash[0] === 'erreur' ? 'message-erreur' : 'message-succes' ?>" role="status">
        <?= htmlspecialchars($flash[1]) ?>
    </div>
<?php endif; ?>

<div class="statistiques">
    <div class="carte"><span class="chiffre"><?= $nbParties ?></span> équipe<?= $nbParties > 1 ? 's' : '' ?></div>
    <div class="carte"><span class="chiffre"><?= $nbAttente ?></span> en attente</div>
    <div class="carte"><span class="chiffre"><?= $nbEnCours ?></span> en cours</div>
    <div class="carte"><span class="chiffre"><?= $nbGagnees ?></span> gagnée<?= $nbGagnees > 1 ? 's' : '' ?></div>
    <div class="carte">
        <span class="chiffre"><?= $meilleurTemps !== null ? formaterTemps($meilleurTemps) : '—' ?></span>
        meilleur temps
    </div>
</div>

<div class="carte">
    <h2>Créer une équipe</h2>
    <form method="POST" action="admin.php">
        <input type="hidden" name="csrf" value="<?= jetonCsrf() ?>">
        <input type="hidden" name="action" value="creer_equipe">
        <label for="nom_equipe">Nom de l'équipe</label>
        <input type="text" id="nom_equipe" name="nom_equipe" maxlength="100" required>
        <div>
            <button type="submit" class="bouton">Créer l'équipe</button>
        </div>
    </form>
    <p class="texte-doux">
        Donnez ensuite le <strong>numéro</strong> de l'équipe aux joueurs : ils se connectent avec
        sur la page d'accueil. Le chrono démarre à leur connexion.
    </p>
</div>

<h2 class="titre-section">Équipes</h2>

<?php if (empty($parties)): ?>
    <div class="carte vide"><p>Aucune équipe pour le moment.</p></div>
<?php else: ?>
    <div class="tableau-conteneur">
        <table class="tableau">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Équipe</th>
                    <th>Statut</th>
                    <th>Code secret</th>
                    <th class="nombre">Essais</th>
                    <th class="nombre">Temps</th>
                    <th>Début</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($parties as $partie): ?>
                    <tr>
                        <td class="rang">#<?= (int) $partie['id'] ?></td>
                        <td><?= htmlspecialchars($partie['nom_equipe']) ?></td>
                        <td><?= htmlspecialchars($libellesStatut[$partie['statut']] ?? $partie['statut']) ?></td>
                        <td class="code-secret">
                            <?php foreach (decoderCombinaisonTexte($partie['combinaison_secrete']) as $code): ?>
                                <span class="badge badge-petit badge-<?= htmlspecialchars($code) ?>"><?= htmlspecialchars($code) ?></span>
                            <?php endforeach; ?>
                        </td>
                        <td class="nombre"><?= (int) $partie['nb_essais'] ?></td>
                        <td class="nombre">
                            <?= $partie['temps_secondes'] !== null ? formaterTemps((int) $partie['temps_secondes']) : '—' ?>
                        </td>
                        <td><?= $partie['statut'] === 'en_attente' ? '—' : htmlspecialchars(date('d/m H:i', strtotime($partie['date_debut']))) ?></td>
                        <td>
                            <?php if (in_array($partie['statut'], ['en_attente', 'en_cours'], true)): ?>
                                <form method="POST" action="admin.php"
                                      onsubmit="return confirm('Terminer la partie de cette équipe ?');">
                                    <input type="hidden" name="csrf" value="<?= jetonCsrf() ?>">
                                    <input type="hidden" name="action" value="terminer">
                                    <input type="hidden" name="id_partie" value="<?= (int) $partie['id'] ?>">
                                    <button type="submit" class="bouton bouton-secondaire bouton-petit">Terminer</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<div class="carte zone-danger">
    <h2>Remise à zéro</h2>
    <p>Supprime toutes les parties et tous les essais (à faire entre deux sessions). Irréversible.</p>
    <form method="POST" action="admin.php"
          onsubmit="return confirm('Supprimer définitivement toutes les parties ?');">
        <input type="hidden" name="csrf" value="<?= jetonCsrf() ?>">
        <input type="hidden" name="action" value="reinitialiser">
        <button type="submit" class="bouton bouton-danger">Tout remettre à zéro</button>
    </form>
</div>

<?php require __DIR__ . '/includes/bas.php'; ?>
