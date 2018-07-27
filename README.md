# KEEPME API (back)

Prérequis :

`Pensez un importer la base de données du 168H-ETNA pour ceux qui ne l'ont pas fait.`

Installation :
```
composer install
```

Réinstallation (après modification des dépendances dans `composer.json`) :
```
rm -rf vendor/ composer.lock
composer install
```

Pour lancer le serveur php en local :
```
php -S localhost:8080 -t public public/index.php
```


On accède via :
```
http://localhost:8080/
```

Routes :

```
Récupération d'utilisateurs :

GET :
/user/:id                  (Récupération d'un utilisateur selon son id)
/users                     (Récupération de tous les utilisateurs)
/organization/:id/users    (Récupération de tous les utilisateurs d'une mairie)

POST :
/user                      (Création d'un utilisateur)

Récupération de mairies :

/organizations             (Récupération de toutes les mairies)
/organization/:id          (Récupération d'une mairie selon son id)

Récupération des villes :

/cities                    (Récupération de toutes les villes)
/city/:id                  (Récupération d'une ville selon son id)
/city/:id/state            (Récupération des states d'une ville)

Récupération des annonces :

/polls                     (Récupération de toutes les annonces)
/poll/:id                  (Récupération d'une annonce selon son id)
/organization/:id/polls    (Récupération des annonces d'une mairie selon son id)

```

Petit prérequis pour Git :

```
Rajout d’une branche :
Faire un fork sur mon compte GitHub
Exécuter :
    - git remote add [nom du remote] [lien du fork] (Ajout de ma remote)
    - git fetch --all (récupération de toutes les modifications faites par une personne)
    - git start feature [numero de feature] (commencement d'une nouvelle fonctionnalité )

Mettre en PR :
- Exécuter :
    - git co [nom de branche]
    - git rebase [nom de branche de destination] (souvent origin/master)
    - git add (permet d’envoyer un dépôt modifier)
    - git ci (permet de commit et d’ajouter un message)
    - git push [nom de remote] [nom de branche]
```
