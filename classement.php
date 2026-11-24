<?php
/* =========================================================
 *  Classement des équipes gagnantes
 *  Tri : temps le plus court, puis nombre d'essais le plus faible
 *  (le tri est fait en SQL dans recupererClassement())
 * ========================================================= */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/fonctions.php';

$classement = recupererClassement($pdo, 20);
$titre      = 'Classement';

/**
 * Convertit des secondes en texte lisible : 125 -> "2 min 05 s"
 */
function formaterTemps(int $secondes): string
{
    $minutes = intdiv($secondes, 60);
    $reste   = $secondes % 60;

    if ($minutes === 0) {
        return $reste . ' s';
    }
    return sprintf('%d min %02d s', $minutes, $reste);
}

require __DIR__ . '/includes/haut.php';
?>

<h1>Classement</h1>
<p>Les équipes qui ont trouvé l'ordre secret, de la plus rapide à la plus lente.</p>

<?php if (empty($classement)): ?>
    <div class="carte vide">
        <p>Aucune équipe n'a encore trouvé l'ordre secret.</p>
        <a href="index.php" class="bouton">Être la première équipe</a>
    </div>
<?php else: ?>
    <div class="tableau-conteneur">
        <table class="tableau">
            <thead>
                <tr>
                    <th>Rang</th>
                    <th>Équipe</th>
                    <th class="nombre">Temps</th>
                    <th class="nombre">Essais</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classement as $i => $ligne): ?>
                    <?php $rang = $i + 1; ?>
                    <tr class="rang-<?= $rang ?>">
                        <td class="rang">#<?= $rang ?></td>
                        <td><?= htmlspecialchars($ligne['nom_equipe']) ?></td>
                        <td class="nombre"><?= formaterTemps((int) $ligne['temps_secondes']) ?></td>
                        <td class="nombre"><?= (int) $ligne['nb_essais'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/includes/bas.php'; ?>
