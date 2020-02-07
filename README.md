rochedor-nsi
============


# ROCHEDOR NSI
## Prérequis
- php7.x
- php-json
- php-pcre
- php-pdo 
- php-sqlite
- node-js v8.15.0
- yarn
- jpegoptim
- curl & php-curl

## Setup
No docker for the app (yet), check `script/bootstrap` for base deps ;
```
sudo apt install php73 php-json php-pdo php-mysql jpegoptim
```


## Lancement
script/bootstrap` Pour installer les dépendances et mettre à jour la bdd (à lancer après chaque `git pull`)   
script/server [-p 8000]` Pour lancer un serveur et la surveillance des assets avec webpack watch (à relancer après chaque modification du fichier `webpack.config.js`)    


## Conventions
- Suivre les directives [PSR2](http://www.php-fig.org/psr/psr-2/) pour le formatage du code php
- Utilisation des [annotations](https://symfony.com/doc/current/best_practices/controllers.html#routing-configuration) pour le routing 
- Les vues dans le répertoire app/Resources/views
- Les assets dans app/Resources/assets
- Les requêtes dans les repositories en [DQL](https://symfony.com/doc/current/doctrine.html#querying-for-objects-with-dql) dans la mesure du possible
- Code (noms de variables et méthodes), commentaires en anglais

## Génération de la doc swagger
- `script/doc`

## GRUMPHP
L'outil [grumphp](https://github.com/phpro/grumphp) surveille le code, si un commit ne passe pas, c'est que le code ne suit pas les conventions.
Pour lancer les vérifications à la main:  
`vendor/bin/grumphp run`

## SMTP
Les environnements de dev et de test sont configurés pour envoyer les emails sur le port 2525.    
Utiliser l'outil [faketools](https://github.com/Bornholm/faketools) pour lancer un serveur smtp local écoutant sur le port 2525     
`docker run --rm -p 2525:2525 -p 8080:8080 -it bornholm/faketools`

<<<<<<< HEAD
## Users
- admin::admin

## Deployment
**WARNING** The deployment needs an extra step! 

The results for `bin/console doctrine:schema:update` is not forced on the database but generated in an sql which must be manually run.

## Create a new page

To create a page, we have a beautiful API. Here is the process :

>Login to the API with you admin credentials
```json
URL [POST] : /api/login 

{
    "username": "XXX",
    "password": "XXX"
}
```

>Create the page
```json
URL [POST] : /api/pages

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

3. Go to the admin interface to customize the content and everything you want

### TODO before prod deployment
- [ ] list/create all pages in all languages
=======

# Production


## Serveur
Les environnements staging, dev sont hébergés sur https://ns3058686.ip-193-70-12.eu:2087 et sont accessibles respectivement sur https://staging.rochedor.fr https://nsi.rochedor.fr 

## Déploiement
Le déploiement des branches dev et staging est automatique

Le script fait un dump du schema update à la racine de l'espace de déploiement
>>>>>>> master
