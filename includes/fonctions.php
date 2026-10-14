<?php
/**
 * FICHIER : includes/fonctions.php
 * RESPONSABLE : Personne 3 (Requêtes SQL & Fonctions métiers)
 * DESCRIPTION : Fonctions faisant le lien entre la base de données MySQL ($pdo)
 *               et les pages Web de l'application.
 */

require_once __DIR__ . '/logique.php';

/**
 * Récupère la liste complète des badges disponibles dans la base de données.
 *
 * @param PDO $pdo Instance de connexion à la BDD
 * @return array Liste associative des badges (id, code_badge, nom, couleur)
 */
function recupererTousLesBadges(PDO $pdo): array
{
    // TODO (Personne 3) : Faire un SELECT * FROM badges ORDER BY id ASC et retourner fetchAll(PDO::FETCH_ASSOC).
    return [];
}

/**
 * Crée une nouvelle partie dans la table `parties` au lancement du jeu.
 *
 * @param PDO $pdo Instance de connexion à la BDD
 * @param string $nomEquipe Nom saisi par le groupe de joueurs sur index.php
 * @param array $combinaisonSecrete Tableau des codes de la combinaison secrète générée
 * @return int L'identifiant (id) de la partie nouvellement créée en base
 */
function creerNouvellePartie(PDO $pdo, string $nomEquipe, array$combinaisonSecrete): int
{
    // TODO (Personne 3) :
    // 1. Convertir $combinaisonSecrete en texte avec formaterCombinaisonEnTexte()
    // 2. Insérer la ligne dans la table `parties` avec requête préparée
    // 3. Retourner (int) $pdo->lastInsertId()
    return 0;
}

/**
 * Récupère les informations d'une partie à partir de son ID.
 *
 * @param PDO $pdo Instance de connexion à la BDD
 * @param int $idPartie Identifiant de la partie en cours
 * @return array|null Tableau associatif contenant les infos de la partie, ou null si introuvable
 */
function recupererPartie(PDO $pdo, int$idPartie): ?array
{
    // TODO (Personne 3) : Faire un SELECT * FROM parties WHERE id = :id et retourner la ligne (ou null).
    return null;
}

/**
 * Enregistre une tentative d'équipe dans la table `tentatives`.
 *
 * @param PDO $pdo Instance de connexion à la BDD
 * @param int $idPartie Identifiant de la partie en cours
 * @param array $proposition Tableau ordonné des codes de badges testés par le joueur
 * @param int $nbCorrects Nombre de badges bien placés calculé par compterBadgesCorrects()
 * @return bool True si l'insertion a réussi, False sinon
 */
function enregistrerTentative(PDO $pdo, int$idPartie, array $proposition, int$nbCorrects): bool
{
    // TODO (Personne 3) :
    // 1. Convertir $proposition en texte avec formaterCombinaisonEnTexte()
    // 2. Faire un INSERT INTO tentatives (id_partie, combinaison_proposee, nb_corrects)
    return false;
}

/**
 * Récupère la liste de toutes les tentatives effectuées lors d'une partie.
 *
 * @param PDO $pdo Instance de connexion à la BDD
 * @param int $idPartie Identifiant de la partie en cours
 * @return array Liste des tentatives (du premier au dernier essai)
 */
function recupererHistoriqueTentatives(PDO $pdo, int$idPartie): array
{
    // TODO (Personne 3) : SELECT * FROM tentatives WHERE id_partie = :id_partie ORDER BY id ASC
    return [];
}

/**
 * Clôture une partie lorsque l'équipe a trouvé la bonne combinaison.
 * Met le statut à 'gagne', enregistre date_fin et calcule temps_secondes.
 *
 * @param PDO $pdo Instance de connexion à la BDD
 * @param int $idPartie Identifiant de la partie gagnée
 * @return bool True si la mise à jour a réussi
 */
function declarerVictoirePartie(PDO $pdo, int$idPartie): bool
{
    // TODO (Personne 3) :
    // UPDATE parties
    // SET statut = 'gagne',
    //     date_fin = NOW(),
    //     temps_secondes = TIMESTAMPDIFF(SECOND, date_debut, NOW())
    // WHERE id = :id
    return false;
}

/**
 * Récupère le classement des meilleures équipes (parties gagnées).
 *
 * @param PDO $pdo Instance de connexion à la BDD
 * @param int $limite Nombre maximum d'équipes à afficher (par défaut 10)
 * @return array Tableau contenant nom_equipe, temps_secondes et le nombre de tentatives
 */
function recupererClassement(PDO $pdo, int$limite = 10): array
{
    // TODO (Personne 3) :
    // Requête SELECT sur `parties` (WHERE statut = 'gagne') avec un COUNT() sur `tentatives`,
    // triée par temps_secondes ASC puis par nombre d'essais ASC.
    return [];
}

/**
 * Fonctions réservées à admin.php : modifie manuellement la combinaison secrète d'une partie
 * ou réinitialise l'historique des parties.
 *
 * @param PDO $pdo Instance de connexion à la BDD
 * @return bool True si la réinitialisation a fonctionné
 */
function reinitialiserToutesLesParties(PDO $pdo): bool
{
    // TODO (Personne 3) : Vider les tables `tentatives` et `parties` (DELETE ou TRUNCATE).
    return false;
}