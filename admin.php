<?php

require_once __DIR__ . '/includes/auth.php';

verifierAdmin();

?>

<?php
/* =========================================================
 *  Espace animateur : statistiques, parties récentes
 *  (avec le code secret, pour aider un joueur bloqué)
 *  et remise à zéro entre deux sessions.
 * ========================================================= */
require_once __DIR__ . '/includes/fonctions.php';

$parties = recupererParties($pdo);

?>

<h1>Espace animateur</h1>

<table>
    <thead>
        <tr>
            <th>Équipe</th>
            <th>Statut</th>
            <th>Code secret</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($parties as $partie): ?>
            <tr>
                <td>
                    <?= htmlspecialchars($partie['nom_equipe']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($partie['statut']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($partie['combinaison_secrete']) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require __DIR__ . '/includes/bas.php'; ?>
