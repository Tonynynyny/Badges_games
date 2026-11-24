<?php

function verifierAdmin(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (($_SESSION['role'] ?? '') !== 'admin') {
        header('Location: connexion.php?type=admin');
        exit;
    }
}

function verifierJoueur(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (($_SESSION['role'] ?? '') !== 'joueur') {
        header('Location: connexion.php?type=joueur');
        exit;
    }
}
