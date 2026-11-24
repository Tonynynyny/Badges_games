    </main>
    <footer class="pied">
        <p>Badges Game — Antilles CyberEscape 2026 · IUT de la Martinique</p>
        <a href="admin.php" class="lien-discret">Espace animateur</a>
    </footer>

    <?php if (($pageCourante ?? '') === 'jeu.php'): ?>
        <script src="assets/jeu.js"></script>
    <?php endif; ?>
</body>
</html>
