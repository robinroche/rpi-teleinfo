# rpi-teleinfo

Relevé de compteur électrique ERDF avec un Raspberry Pi.

### License

Aucune. Faites en ce que vous voulez.

### Objectifs

Le système de Téléinfo disponible sur les compteurs électriques d'ERDF permettent de récupérer les données de consommation. Le montage décrit ci-dessous permet de récupérer automatiquement la consommation du compteur toutes les minutes et de l'afficher en ligne sur un graphe.

### Matériel et montage

On utilise pour cela le [même montage électronique que pour l'Arduino](https://github.com/robinroche/arduino-teleinfo), basé sur un optocoupleur SFH620A.

On pourra se référer à d'autres site pour des détails sur le montage, comme [celui-ci](http://www.magdiblog.fr/gpio/teleinfo-edf-suivi-conso-de-votre-compteur-electrique/) par exemple. J'ai toutefois constaté qu'ajouter une liaison à un PIN 3.3V du RPi (seule différence par rapport au montage pour Arduino) semble empêcher le système de fonctionner, la lecture des données n'étant plus fonctionnelle. N'y connaissant pas grand chose en électronique, la raison m'échappe...

La photo ci-dessous montre le système complet (compteur, RPi, petit montage électronique) branché et fonctionnel. Le RPi est connecté à internet via un dongle Wifi.

![Photo du montage réalisé](http://robinroche.com/webpage/images/400px-IMG_6326.JPG)

### Fonctionnement de la partie logicielle

En termes de logiciel, le système fonctionne comme suit :
- Lecture des données du compteur sur le RPi via l'optocoupleur, toutes les minutes,
- Extraction et vérification des données utiles,
- Envoi des données sur un serveur via un GET,
- Extraction des données du GET et stockage dans une base de données,
- Affichage des données dans un graphe après extraction de la base.

### Structure du code

Le code source est organisé autour des fichiers principaux suivants :

- lecture_teleinfo.py: ce script en Python réalise la lecture du flux envoyé par le compteur sur le Raspberry Pi, et extrait les données utiles : la consommation instantanée (PAPP, puissance apparente en VA, ici notée en W pour simplifier), et le tarif heure pleine ou creuse (PTEC).
- upload_data.php: ce script reçoit les données envoyées par le Raspberry Pi sur le serveur et les stocke dans une base de données MySQL.
- display.php: ce script sur le serveur extrait les données de la base de données MySQL et les affiche à l'aide de HighCharts.

La table MySQL est organisée comme le montre la figure ci-dessous.

![Structure de la table MySQL](http://robinroche.com/webpage/images/Table.PNG)

### Installation

Sur le RPi :

- Copier le contenu du fichier lecture_teleinfo.py dans un nouveau fichier dans le répertoire /home/pi.
- Dans ce fichier, modifier l'adresse du serveur et le mot de passe.
- Désactiver l'utilisation du port série par Linux, en suivant les [instructions décrites ici](http://elinux.org/RPi_Serial_Connection#Preventing_Linux_using_the_serial_port).
- Lancer le script lecture_teleinfo.py à chaque minute via cron.

Sur le serveur :

- Créer la base de données et la table comme indiqué plus haut.
- Uploader les deux fichiers PHP, et modifier les logins/mdp pour l'accès à la base de données.
- Dans upload_data.php, modifier le mot de passe pour qu'il corresponde à celui entré sur le RPi.

### Exemples de résultats

Un exemple des résultats obtenus est visible dans la figure ci-dessous. Les données affichées sont la puissance instantanée consommée et le tarif (heures pleines ou creuses).

![Exemple de relevé](http://robinroche.com/webpage/images/Screenshot.PNG)

### Limitations

- Le code ci-dessous est fonctionnel, et a fonctionné pendant plusieurs jours consécutifs. 
- Il n'est cependant clairement pas optimisé ni sécurisé. 
- Par exemple, il n'y a pas de vérification du checksum, la boucle de lecture peut ne jamais s'arrêter, etc. 
- Il s'agit donc d'un code de test, rien de plus.

### Contact

Robin Roche - robinroche.com
