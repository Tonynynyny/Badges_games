# Jeu des Badges — V1 

Épreuve de logique pour une intervention grand public : le joueur doit retrouver
l'**ordre secret de 4 badges**. Après chaque passage des 4 badges, la machine indique
seulement **combien sont à la bonne position**.

Cette V1 simule entièrement le lecteur de badges dans le navigateur :
aucun Raspberry Pi ni capteur n'est nécessaire.

> Exemple : ordre secret `ABCD`
> - le joueur propose `ACBD` → **2** (A et D sont à leur place)
> - le joueur propose `BCDA` → **0** (aucun badge à sa place)

