<?php
/* =========================================================
 *  Classement des parties gagnées (vue SQL v_classement)
 * ========================================================= */
require_once __DIR__ . '/includes/fonctions.php';

$classement = getClassement(20);
$titre      = 'Classement';
require __DIR__ . '/includes/haut.php';
?>


<?php require __DIR__ . '/includes/bas.php'; ?>
