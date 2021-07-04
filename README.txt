Lancer l'application

utiliser xampp pour créer un servuer apache et mysql

creer un db app dans mysql

mettre a jour les infomation de conexion a la db dans le fichier config.php

copier tout les fichier dans le serveur apache  sous le repertoire C:\xampp\htdocs\app

Page client d'authentification
http://localhost/app/client/authentification.php
Page d'administration
http://localhost/app/administrateur/operateur_page.php
Page des offres des operateurs
http://localhost/app/client/

Structure de dossier

Le dossier admnistrateur contien les page  de l'applicat web administrateur
il y a une page par table
chaque page contien un tableau qui represente la table en db


Le dossier api contien le code php qui permet d'avoir acces a la base de données
il y a un dossier par table et dans chacun de ces dossier il y a un fichier php qui représente une opération sur cette table
chaque operation affiche le resultat sous format JSON ce qui permet de l'utiliser avec du code javascript

Le dossier client contient les page de l'application web client

Le dossier css contient les feuille de stype utiliser par toute les page

Le dossier js contient les lib js utilisée

Le fichier config.php
contient les info de conexion a la base de donnée

Le fichier database.sql
contient le script pour créer la base de données


Langague et librarie utiliser
PHP pour la gestion de la persistance des données et la session utilisatuer
Vuejs framework javascript  pour avoir des page dynamique
Vuetify librarie de composant et style pour vuejs permet d'avoir de beau  composant réutilisable
Axios pour faire des appel http facilement en javascript




