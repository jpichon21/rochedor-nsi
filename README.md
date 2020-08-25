rochedor-nsi
============

Ce projet est prévu pour fonctionner sous une architecture UNIX (ex: Linux, OSX).

# ROCHEDOR NSI
## Prérequis
- php7.2
- composer
- php-json
- php-pcre
- php-pdo 
- php-sqlite
- node-js v8.15.0
- yarn
- jpegoptim
- curl & php-curl
- git
- deployer
- gitlab
- apache
- mysql 5.6

## Lancement
`script/bootstrap` Pour installer les dépendances et mettre à jour la bdd (à lancer après chaque `git pull`)   
`script/server` Pour lancer un serveur et la surveillance des assets avec webpack watch (à relancer après chaque modification du fichier `webpack.config.js`)    

## Conventions
- Suivre les directives [PSR2](http://www.php-fig.org/psr/psr-2/) pour le formatage du code php
- Utilisation des [annotations](https://symfony.com/doc/current/best_practices/controllers.html#routing-configuration) pour le routing 
- Les vues dans le répertoire app/Resources/views
- Les assets (images, styles, js) dans app/Resources/assets
- Les requêtes dans les repositories en [DQL](https://symfony.com/doc/current/doctrine.html#querying-for-objects-with-dql) dans la mesure du possible
- Code (noms de variables et méthodes) en anglais

## Déploiement
**ATTENTION** 

Le résultat de la commande `php bin/console doctrine:schema:update` (lancé automatiquement à chaque déploiement) ne modifie pas le schéma de la base de données mais écrit dans un fichier update.sql les requêtes à exécuter.

## Créer une page dans le CMS


>Se connecter à l'API avec ses identifiants administrateurs
```json
URL [POST] : https://XXX/api/login 

BODY
{
    "username": "XXX",
    "password": "XXX"
}
```

>Créer la page
```json
URL [POST] : https://XXX/api/pages

BODY
{
    "title": "XXX",
    "sub_title": "X",
    "description": "XXX",
    "content": {
        "intro": "",
        "sections": [
            {
                "title": "",
                "body": "<p></p>\n",
                "slides": [
                    {
                        "layout": "1-1-2",
                        "images": [
                            {
                                "type": "",
                                "url": "",
                                "alt": "",
                                "video": ""
                            },
                            {
                                "type": "",
                                "url": "",
                                "alt": "",
                                "video": ""
                            },
                            {
                                "type": "",
                                "url": "",
                                "alt": "",
                                "video": ""
                            },
                            {
                                "type": "",
                                "url": "",
                                "alt": "",
                                "video": ""
                            }
                        ]
                    }
                ]
            }
        ]
    },
    "locale": "fr",
    "url": "XXX",
    "category": ""
}
```

3. Vérifier que la page est bien créée dans l'interface d'administration et la remplir avec les bonnes informations

# Production

## Serveur
Les environnements nsi, demo-front et production sont hébergés sur https://ns3058686.ip-193-70-12.eu:2087 et sont accessibles respectivement sur https://nsi.rochedor.fr (branche dev dans git | dossier nsi_dev sur le serveur), https://demo-front.rochedor.fr (branche demo_front dans git | dossier nsi_demo_front sur le serveur) et https://rochedor.fr (branche prod dans git | dossier nsi_staging sur le serveur) 

## Déploiement
Le déploiement de la branche dev est automatique avec GitlabCI après chaque push.
Le déploiement de la branche demo_front est automatique avec GitlabCI après chaque push.
Le déploiement de la branche prod est manuel avec GitlabCI après chaque push.

Le déploiement peut aussi se faire manuellement depuis un terminal via deployer. 
