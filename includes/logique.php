<?php
/**
 * FICHIER : includes/logique.php
 * RESPONSABLE : Jeffrey (Moteur & Logique du jeu)
 * DESCRIPTION : Fonctions pures de génération et de vérification de l'ordre des badges.
 *               Aucune requête SQL ne doit figurer dans ce fichier.
 *
 * Toutes les fonctions manipulent des "codes de badge" (colonne badges.code_badge) :
 *   - en V1 : 'BADGE_ROUGE', 'BADGE_BLEU', ...
 *   - en V2 : l'UID RFID du badge (ex. 'A1B2C3D4'), sans rien changer ici.
 *
 * Règle de sécurité : la combinaison secrète est tirée et comparée côté serveur
 * uniquement. Aucune de ces fonctions ne doit être appelée pour envoyer le secret
 * au navigateur.
 */

/**
 * Génère aléatoirement une combinaison secrète à partir des badges disponibles.
 * Les badges tirés sont tous distincts. Le tirage utilise random_int(), un
 * générateur cryptographiquement sûr : l'ordre secret n'est pas prévisible.
 *
 * @param array $listeCodesBadges Tableau contenant tous les code_badge existants (ex: ['BADGE_ROUGE', 'BADGE_BLEU', ...])
 * @param int $taille Nombre de badges dans la combinaison à trouver (par défaut 4)
 * @return array Tableau ordonné des codes choisis (ex: ['BADGE_BLEU', 'BADGE_ROUGE', 'BADGE_JAUNE', 'BADGE_VERT'])
 * @throws InvalidArgumentException Si $taille < 1 ou s'il y a moins de $taille badges distincts
 */
function genererCombinaisonSecrete(array $listeCodesBadges, int $taille = 4): array
{
    // On ignore les doublons éventuels et on réindexe (0, 1, 2, ...)
    $codes = array_values(array_unique($listeCodesBadges));

    if ($taille < 1) {
        throw new InvalidArgumentException('La taille de la combinaison doit être au moins 1.');
    }
    if (count($codes) < $taille) {
        throw new InvalidArgumentException(
            'Il faut au moins ' . $taille . ' badges distincts en base, ' . count($codes) . ' trouvé(s).'
        );
    }

    // Mélange de Fisher-Yates avec random_int() (shuffle() n'est pas fait pour un secret)
    for ($i = count($codes) - 1; $i > 0; $i--) {
        $j = random_int(0, $i);
        [$codes[$i], $codes[$j]] = [$codes[$j], $codes[$i]];
    }

    return array_slice($codes, 0, $taille);
}

/**
 * Compare la combinaison proposée par les joueurs avec la combinaison secrète.
 * Rappel : Seul le nombre de badges placés au bon endroit ("corrects") est comptabilisé.
 *
 * Exemple avec le secret ABCD : ACBD -> 2 (A et D), BCDA -> 0.
 *
 * @param array $proposition Tableau ordonné des codes proposés par le joueur
 * @param array $secret Tableau ordonné de la combinaison secrète
 * @return int Nombre exact de badges à la bonne position (entre 0 et la taille de la combinaison)
 */
function compterBadgesCorrects(array $proposition, array $secret): int
{
    // Réindexation : on compare des positions 0, 1, 2, 3 quelles que soient les clés reçues
    $proposition = array_values($proposition);
    $secret      = array_values($secret);

    $nbCorrects = 0;
    foreach ($secret as $position => $codeSecret) {
        if (array_key_exists($position, $proposition) && $proposition[$position] === $codeSecret) {
            $nbCorrects++;
        }
    }

    return $nbCorrects;
}

/**
 * Vérifie si le nombre de badges corrects correspond à une victoire.
 *
 * @param int $nbCorrects Nombre de badges bien placés obtenus lors de l'essai
 * @param int $tailleAttendue Nombre total de badges dans la combinaison (par défaut 4)
 * @return bool True si tous les badges sont au bon endroit, False sinon
 */
function estCombinaisonGagnante(int $nbCorrects, int $tailleAttendue = 4): bool
{
    return $tailleAttendue > 0 && $nbCorrects === $tailleAttendue;
}

/**
 * Convertit un tableau de codes de badges en chaîne de caractères pour le stockage en BDD.
 * Exemple : ['BADGE_ROUGE', 'BADGE_BLEU'] -> 'BADGE_ROUGE,BADGE_BLEU'
 *
 * @param array $combinaison Tableau de codes de badges
 * @return string Chaîne séparée par des virgules
 */
function formaterCombinaisonEnTexte(array $combinaison): string
{
    return implode(',', $combinaison);
}

/**
 * Convertit une chaîne de caractères issue de la BDD en tableau PHP de codes de badges.
 * Exemple : 'BADGE_ROUGE,BADGE_BLEU' -> ['BADGE_ROUGE', 'BADGE_BLEU']
 * Les espaces autour des codes et les éléments vides sont ignorés.
 *
 * @param string $combinaisonTexte Chaîne séparée par des virgules
 * @return array Tableau ordonné de codes de badges
 */
function decoderCombinaisonTexte(string $combinaisonTexte): array
{
    if (trim($combinaisonTexte) === '') {
        return [];
    }

    $codes = array_map('trim', explode(',', $combinaisonTexte));

    // Supprime les éléments vides (ex. 'A,,B' ou virgule finale) puis réindexe
    return array_values(array_filter($codes, fn(string $code): bool => $code !== ''));
}
