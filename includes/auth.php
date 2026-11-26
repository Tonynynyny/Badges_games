<?php

function verifierAdmin(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (($_SESSION['role'] ?? '') !== 'admin') {
        header('Location: index.php?type=admin');
        exit;
    }
}

function verifierJoueur(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (($_SESSION['role'] ?? '') !== 'joueur') {
        header('Location: index.php?type=joueur');
        exit;
    }
}
