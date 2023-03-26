# Projet SymRbnB avec symfony

Dans ce projet nous allons créer un site de location de logement entre particuliers style Airbnb.

## Installation
Créer un projet symfony avec la commande suivante :
`symfony new symrbnb --webapp`

## Création de la base de données

Créer une base de données sqlite avec le nom `symrbnb`

`symfony console doctrine:database:create`

## Création des entités

`symfony console make:entity`
`symfony console make:migration`
`symfony console doctrine:migrations:migrate`

Créer les entités suivantes :

- Ad (annonce) avec les champs suivants :
    - title (string) Nullable = false
    - slug (string) Nullable = false
    - Price (float) Nullable = false
    - Type (string) Nullable = false(Maison, Appartement, Chambre, Villa, Loft)
    - coverImage (string) Nullable = false
    - introduction (text) Nullable = false
    - content (text) Nullable = false
    - rooms (integer) Nullable = false
    - createdAt (datetimeImmutable) Nullable = false
    - updatedAt (datetimeImmutable) Nullable = true
    - author (User) Relation ManyToOne avec User (nullable = false) `ManyToOne car une annonce ne peut avoir qu'un seul auteur` 

- Image (image) avec les champs suivants :
    - url (string) Nullable = false
    - caption (string) Nullable = false
    - ad (Ad) Relation ManyToOne avec Ad (nullable = false) `ManyToOne car une annonce peut avoir plusieurs images`

- User (utilisateur) avec les champs suivants :
    - firstName (string) Nullable = false
    - lastName (string) Nullable = false
    - email (string) Nullable = false
    - password (string) Nullable = false
    - introduction (text) Nullable = false
    - presentation (text) Nullable = false
    - slug (string) Nullable = false
    - address (string) Nullable = false
    - city (string) Nullable = false
    - postalCode (string) Nullable = false
    - country (string) Nullable = false
    - profilPicture (string) Nullable = yes
    - createdAt (datetimeImmutable) Nullable = false
    - updatedAt (datetimeImmutable) Nullable = true




## Création des fixtures
`composer require --dev orm-fixtures`
`symfony console make:fixtures`
`composer require fakerphp/faker`
`symfony console doctrine:fixtures:load`

- créer une fixture pour les Ad

En utilisant le lifecycle event, on va créer un slug pour chaque annonce et le createdAt  pour chaque annonce.`#[ORM\HasLifecycleCallbacks]`
Le cycle de vie d'une entité est une série d'événements qui se déclenchent à des moments précis de la vie d'une entité.Elle nous permet de déclencher des actions à des moments précis de la vie d'une entité.
`https://symfony.com/doc/current/doctrine/events.html` 
Le cycle de vie d'une entité est composé de 3 événements : la pré-persistance, la pré-update et la post-load.
`la pré-persistance` : permet de créer un slug pour chaque annonce et le createdAt pour chaque annonce.
`la pré-update` : permet de mettre à jour le updatedAt pour chaque annonce.
`la post-load` : permet de mettre à jour le updatedAt pour chaque annonce.

- Créer une méthode `initializeSlugAndCreated()` dans l'entité Ad pour créer le slug et le createdAt pour chaque annonce.

- Création du adController et de la route pour afficher la liste des annonces avec la commande suivante :
`symfony console make:controller`
Afficher la liste des annonces dans le fichier `ad/index.html.twig`
Afficher le détail d'une annonce dans le fichier `ad/show.html.twig`


## Authentification et autorisation

`symfony console make:user`
`symfony console make:registration-form`
`symfony console make:auth`

- Gérer l'inscription et la connexion des utilisateurs après les commandes ci-dessus.

- Edition du profil utilisateur avec la commande suivante :
`symfony console make:form accountType`

- Modification du mot de passe avec la commande suivante :
`symfony console make:entity passwordUpdate`
 - Entity PasswordUpdate
    - oldPassword (string) Nullable = false
    - newPassword (string) Nullable = false
    - confirmPassword (string) Nullable = false
    `supprimer les annotations ORM car on ne va pas créer de table pour cette entité`

Ajouter les assertions `use Symfony\Component\Validator\Constraints as Assert;`

- Création du formulaire PasswordUpdateType
`symfony console make:form passwordUpdateType`

- Finalisation de l'interface en ajoutant les users de chaque annonce dans le fichier `ad/show.html.twig`


## Creation et gestion des réservations

- Création de l'entité Booking avec la commande suivante :
`symfony console make:entity`
 - Entity Booking
    - startDate (datetimeImmutable) Nullable = false
    - endDate (datetimeImmutable) Nullable = false
    - createdAt (datetimeImmutable) Nullable = false
    - amount (float) Nullable = false
    - booker (User) Relation ManyToOne avec User (nullable = false)
    - ad (Ad) Relation ManyToOne avec Ad (nullable = false)
    - comment (string) Nullable = true // Pour ajouter un commentaire sur la réservation

- Creation des fixtures pour les bookings

- Création du crud
`symfony console make:crud Booking`

- Creation de l'entité Comment avec la commande suivante :
`symfony console make:entity`
 - Entity Comment
    - createdAt (datetime) Nullable = false
    - rating (integer) Nullable = false pour la note
    - content (text) Nullable = false
    - author (User) Relation ManyToOne avec User (nullable = false)
    - ad (Ad) Relation ManyToOne avec Ad (nullable = false)

## CREATION ADMIN DASHBOARD

- Installation de EasyAdminBundle
`composer require easycorp/easyadmin-bundle`
`symfony console make:admin:dashboard`
`symfony console make:admin:crud`

## Pagination

- gestion de la pagination en utilisant un service. Créer un dossier `src/Service` et un fichier `Pagination.php`


