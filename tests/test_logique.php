<?php
/* =========================================================
 *  Tests de la logique du jeu (sans base de données)
 *  Lancer : php tests/test_logique.php
 *  Code de sortie : 0 si tout passe, 1 s'il y a au moins un échec.
 * ========================================================= */
require_once __DIR__ . '/../includes/logique.php';

$nbReussis = 0;
$echecs    = [];

/** Enregistre le résultat d'une vérification et l'affiche. */
function verifier(string $description, bool $condition): void
{
    global $nbReussis, $echecs;
    if ($condition) {
        $nbReussis++;
        echo "  [OK]    $description\n";
    } else {
        $echecs[] = $description;
        echo "  [ECHEC] $description\n";
    }
}

/** Vérifie que l'appel lève bien une InvalidArgumentException. */
function verifierException(string $description, callable $appel): void
{
    try {
        $appel();
        verifier($description, false);
    } catch (InvalidArgumentException $e) {
        verifier($description, true);
    }
}

function section(string $titre): void
{
    echo "\n== $titre ==\n";
}

$badges4 = ['BADGE_ROUGE', 'BADGE_BLEU', 'BADGE_VERT', 'BADGE_JAUNE'];
$badges6 = ['BADGE_ROUGE', 'BADGE_BLEU', 'BADGE_VERT', 'BADGE_JAUNE', 'BADGE_ORANGE', 'BADGE_VIOLET'];

/* ---------------------------------------------------------
 * 1. Tirage de la combinaison secrète
 * --------------------------------------------------------- */
section('Tirage de la combinaison secrète');

foreach (['4 badges' => $badges4, '6 badges' => $badges6] as $libelle => $badges) {
    $tiragesOk = true;
    for ($k = 0; $k < 500; $k++) {
        $secret = genererCombinaisonSecrete($badges);
        if (
            count($secret) !== 4
            || count(array_unique($secret)) !== 4
            || array_diff($secret, $badges) !== []
            || array_keys($secret) !== [0, 1, 2, 3]
        ) {
            $tiragesOk = false;
            break;
        }
    }
    verifier("Avec $libelle : 500 tirages de 4 badges distincts, tous issus de la base", $tiragesOk);
}

// Avec 4 badges il existe 4! = 24 ordres : on doit tous les voir sur 3000 tirages
// (probabilité d'en rater un : moins de 1 sur 10^50).
$ordresVus = [];
for ($k = 0; $k < 3000; $k++) {
    $ordresVus[formaterCombinaisonEnTexte(genererCombinaisonSecrete($badges4))] = true;
}
verifier('Avec 4 badges : les 24 ordres possibles sortent (tirage vraiment aléatoire)', count($ordresVus) === 24);

// Avec 6 badges, chaque badge doit pouvoir apparaître dans le secret
$badgesVus = [];
for ($k = 0; $k < 500; $k++) {
    foreach (genererCombinaisonSecrete($badges6) as $code) {
        $badgesVus[$code] = true;
    }
}
verifier('Avec 6 badges : chacun des 6 badges peut être tiré', count($badgesVus) === 6);

$copie = $badges6;
genererCombinaisonSecrete($copie);
verifier('Le tableau des badges passé en paramètre n\'est pas modifié', $copie === $badges6);

$secret = genererCombinaisonSecrete(['A', 'B', 'A', 'C', 'B', 'D']);
verifier('Les doublons de la liste sont ignorés (4 badges distincts tirés)', count(array_unique($secret)) === 4);

verifier('Taille personnalisée : 3 badges tirés sur 6', count(genererCombinaisonSecrete($badges6, 3)) === 3);

verifierException('Moins de 4 badges en base -> exception', fn() => genererCombinaisonSecrete(['A', 'B', 'C']));
verifierException('4 codes dont un doublon -> exception', fn() => genererCombinaisonSecrete(['A', 'B', 'C', 'C']));
verifierException('Liste vide -> exception', fn() => genererCombinaisonSecrete([]));
verifierException('Taille 0 -> exception', fn() => genererCombinaisonSecrete($badges4, 0));

/* ---------------------------------------------------------
 * 2. Comptage des badges bien placés
 * --------------------------------------------------------- */
section('Comptage des badges bien placés');

$abcd = ['A', 'B', 'C', 'D'];
verifier('Exemple du cahier des charges : ACBD contre ABCD -> 2', compterBadgesCorrects(['A', 'C', 'B', 'D'], $abcd) === 2);
verifier('Exemple du cahier des charges : BCDA contre ABCD -> 0', compterBadgesCorrects(['B', 'C', 'D', 'A'], $abcd) === 0);
verifier('ABCD contre ABCD -> 4', compterBadgesCorrects($abcd, $abcd) === 4);
verifier('DCBA contre ABCD -> 0', compterBadgesCorrects(['D', 'C', 'B', 'A'], $abcd) === 0);
verifier('ABDC contre ABCD -> 2', compterBadgesCorrects(['A', 'B', 'D', 'C'], $abcd) === 2);
verifier('ADCB contre ABCD -> 2', compterBadgesCorrects(['A', 'D', 'C', 'B'], $abcd) === 2);
verifier('BACD contre ABCD -> 2', compterBadgesCorrects(['B', 'A', 'C', 'D'], $abcd) === 2);
verifier('ABCE contre ABCD -> 3 (possible avec 6 badges : E n\'est pas dans le secret)',compterBadgesCorrects(['A', 'B', 'C', 'E'], $abcd) === 3);

$secretReel = ['BADGE_ROUGE', 'BADGE_BLEU', 'BADGE_VERT', 'BADGE_JAUNE'];
verifier(
    'Avec de vrais codes de badges -> 2',
    compterBadgesCorrects(['BADGE_ROUGE', 'BADGE_VERT', 'BADGE_BLEU', 'BADGE_JAUNE'], $secretReel) === 2
);
verifier(
    'Avec des UID RFID (V2) -> 1',
    compterBadgesCorrects(['04A1B2C3', '9F00AA11', '7C3D2E1F', '11223344'], ['04A1B2C3', '7C3D2E1F', '9F00AA11', '55667788']) === 1
);
verifier('La comparaison respecte la casse (badge_rouge ≠ BADGE_ROUGE)', compterBadgesCorrects(['badge_rouge'], ['BADGE_ROUGE']) === 0);
verifier('Les clés du tableau reçu sont ignorées, seul l\'ordre compte', compterBadgesCorrects([5 => 'A', 9 => 'B', 2 => 'C', 7 => 'D'], $abcd) === 4);
verifier('Proposition plus courte que le secret -> pas d\'erreur', compterBadgesCorrects(['A', 'B'], $abcd) === 2);
verifier('Proposition plus longue que le secret -> au plus la taille du secret', compterBadgesCorrects(['A', 'B', 'C', 'D', 'E'], $abcd) === 4);
verifier('Proposition vide -> 0', compterBadgesCorrects([], $abcd) === 0);

// Sur des secrets tirés au hasard : le secret lui-même donne 4, une rotation donne 0
$comptageOk = true;
for ($k = 0; $k < 200; $k++) {
    $s        = genererCombinaisonSecrete($badges6);
    $rotation = array_merge(array_slice($s, 1), [$s[0]]);
    if (compterBadgesCorrects($s, $s) !== 4 || compterBadgesCorrects($rotation, $s) !== 0) {
        $comptageOk = false;
        break;
    }
}
verifier('200 secrets aléatoires : le secret donne 4, sa rotation donne 0', $comptageOk);

/* ---------------------------------------------------------
 * 3. Détection de la victoire
 * --------------------------------------------------------- */
section('Détection de la victoire (4/4)');

verifier('4 bien placés sur 4 -> victoire', estCombinaisonGagnante(4) === true);
verifier('3 bien placés sur 4 -> pas de victoire', estCombinaisonGagnante(3) === false);
verifier('0 bien placé sur 4 -> pas de victoire', estCombinaisonGagnante(0) === false);
verifier('5 sur 4 (valeur incohérente) -> pas de victoire', estCombinaisonGagnante(5) === false);
verifier('0 sur 0 -> pas de victoire', estCombinaisonGagnante(0, 0) === false);
verifier('3 sur 3 (taille personnalisée) -> victoire', estCombinaisonGagnante(3, 3) === true);
verifier(
    'Enchaînement complet : comptage puis victoire',
    estCombinaisonGagnante(compterBadgesCorrects($secretReel, $secretReel), count($secretReel))
);

/* ---------------------------------------------------------
 * 4. Conversion texte <-> tableau (stockage en base)
 * --------------------------------------------------------- */
section('Conversion pour la base de données');

verifier('Tableau -> texte', formaterCombinaisonEnTexte($secretReel) === 'BADGE_ROUGE,BADGE_BLEU,BADGE_VERT,BADGE_JAUNE');
verifier('Texte -> tableau', decoderCombinaisonTexte('BADGE_ROUGE,BADGE_BLEU,BADGE_VERT,BADGE_JAUNE') === $secretReel);
verifier('Aller-retour sur un secret tiré au hasard', decoderCombinaisonTexte(formaterCombinaisonEnTexte($s = genererCombinaisonSecrete($badges6))) === $s);
verifier('Tableau vide -> chaîne vide', formaterCombinaisonEnTexte([]) === '');
verifier('Chaîne vide -> tableau vide', decoderCombinaisonTexte('') === []);
verifier('Chaîne d\'espaces -> tableau vide', decoderCombinaisonTexte('   ') === []);
verifier('Espaces autour des codes ignorés', decoderCombinaisonTexte(' A , B ,C') === ['A', 'B', 'C']);
verifier('Éléments vides ignorés (A,,B,)', decoderCombinaisonTexte('A,,B,') === ['A', 'B']);
verifier('Un seul badge', decoderCombinaisonTexte('BADGE_ROUGE') === ['BADGE_ROUGE']);
verifier(
    'Le secret stocké en base donne le même comptage que le secret d\'origine',
    compterBadgesCorrects(['A', 'C', 'B', 'D'], decoderCombinaisonTexte(formaterCombinaisonEnTexte($abcd))) === 2
);

/* ---------------------------------------------------------
 * Bilan
 * --------------------------------------------------------- */
$total = $nbReussis + count($echecs);
echo "\n----------------------------------------\n";
if ($echecs === []) {
    echo "SUCCES : $nbReussis/$total tests passés.\n";
    exit(0);
}

echo 'ECHEC : ' . count($echecs) . "/$total test(s) en échec :\n";
foreach ($echecs as $description) {
    echo "  - $description\n";
}
exit(1);
