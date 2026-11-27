<?php
/* =========================================================
 *  Connexion à la base avec PDO (une seule connexion par requête HTTP)
 *  Après inclusion de ce fichier, la variable $pdo est disponible.
 * ========================================================= */

require_once __DIR__ . '/../config.php';

/* ---------------------------------------------------------
 *  Page d'erreur « propre » pour les joueurs et l'animateur.
 *  Le vrai message technique est écrit dans le journal PHP
 *  (WAMP : C:\wamp64\logs\php_error.log) et affiché seulement
 *  si AFFICHER_ERREURS vaut true dans config.php.
 * --------------------------------------------------------- */
if (!function_exists('afficherPageErreur')) {
    function afficherPageErreur(Throwable $e, string $message = 'Une erreur est survenue.'): void
    {
        error_log('[Badges Game] ' . get_class($e) . ' : ' . $e->getMessage()
            . ' dans ' . $e->getFile() . ':' . $e->getLine());

        $details = (defined('AFFICHER_ERREURS') && AFFICHER_ERREURS)
            ? '<pre style="white-space:pre-wrap;text-align:left">' . htmlspecialchars($e->getMessage()) . '</pre>'
            : '';

        if (!headers_sent()) {
            http_response_code(500);
            echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8">'
               . '<meta name="viewport" content="width=device-width, initial-scale=1.0">'
               . '<title>Oups — Badges Game</title><link rel="stylesheet" href="assets/style.css"></head>'
               . '<body><main class="contenu">';
        }

        echo '<div class="carte" style="max-width:520px;margin:2rem auto;text-align:center">'
           . '<h1>Oups !</h1>'
           . '<p>' . htmlspecialchars($message) . '</p>'
           . '<p>Prévenez l\'animateur, puis réessayez dans un instant.</p>'
           . '<p><a href="index.php">Retour à l\'accueil</a></p>'
           . $details
           . '</div>';

        if (!headers_sent()) {
            echo '</main></body></html>';
        }
        exit;
    }

    // Toute erreur non prévue (requête SQL, table manquante…) passe par ici
    set_exception_handler(function (Throwable $e) {
        afficherPageErreur($e);
    });
}

if (!isset($pdo)) {
    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
        DB_HOST,
        DB_PORT,
        DB_NAME
    );

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        afficherPageErreur($e, 'Le jeu est momentanément indisponible.');
    }
}
