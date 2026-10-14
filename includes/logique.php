<?php
/**
 * FICHIER : includes/logique.php
 * RESPONSABLE : Personne 2 (Moteur & Logique du jeu)
 * DESCRIPTION : Fonctions pures de génération et de vérification de l'ordre des badges.
 *               Aucune requête SQL ne doit figurer dans ce fichier.
 */

/**
 * Génère aléatoirement une combinaison secrète à partir des badges disponibles.
 *
 * @param array $listeCodesBadges Tableau contenant tous les code_badge existants (ex: ['BADGE_ROUGE', 'BADGE_BLEU', ...])
 * @param int $taille Nombre de badges dans la combinaison à trouver (par défaut 4)
 * @return array Tableau ordonné des codes choisis (ex: ['BADGE_BLEU', 'BADGE_ROUGE', 'BADGE_JAUNE', 'BADGE_VERT'])
 */
function genererCombinaisonSecrete(array $listeCodesBadges, int $taille = 4): array
{
    // TODO (Personne 2) : Mélanger le tableau $listeCodesBadges et retourner uniquement le nombre d'éléments défini par $taille.
    return [];
}

/**
 * Compare la combinaison proposée par les joueurs avec la combinaison secrète.
 * Rappel : Seul le nombre de badges placés au bon endroit ("corrects") est comptabilisé.
 *
 * @param array $proposition Tableau ordonné des codes proposés par le joueur
 * @param array $secret Tableau ordonné de la combinaison secrète
 * @return int Nombre exact de badges à la bonne position (entre 0 et la taille de la combinaison)
 */
function compterBadgesCorrects(array $proposition, array $secret): int
{
    // TODO (Personne 2) : Parcourir les deux tableaux indice par indice et compter combien de fois $proposition[$i] === $secret[$i].
    return 0;
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
    // TODO (Personne 2) : Retourner true si $nbCorrects est strictement égal à $tailleAttendue (et > 0).
    return false;
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
    // TODO (Personne 2) : Utiliser implode() pour transformer le tableau en chaîne séparée par des virgules.
    return '';
}

/**
 * Convertit une chaîne de caractères issue de la BDD en tableau PHP de codes de badges.
 * Exemple : 'BADGE_ROUGE,BADGE_BLEU' -> ['BADGE_ROUGE', 'BADGE_BLEU']
 *
 * @param string $combinaisonTexte Chaîne séparée par des virgules
 * @return array Tableau ordonné de codes de badges
 */
function decoderCombinaisonTexte(string $combinaisonTexte): array
{
    // TODO (Personne 2) : Utiliser explode() pour retransformer la chaîne en tableau PHP. Gérer le cas d'une chaîne vide.
    return [];
}