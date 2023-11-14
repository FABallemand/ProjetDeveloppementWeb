# 🛜 Projet Developpement Web: Evaluation

Pour une meilleure lisibilité rendez-vous sur: [https://github.com/FABallemand/ProjetDeveloppementWeb/blob/main/EVAL.md](https://github.com/FABallemand/ProjetDeveloppementWeb/blob/main/EVAL.md)

Je recommande de lire le fichier *README.md* avant de commencer l'évaluation. Pour une meilleure lisibilité rendez-vous sur: [https://github.com/FABallemand/ProjetDeveloppementWeb/blob/main/README.md](https://github.com/FABallemand/ProjetDeveloppementWeb/blob/main/README.md)

## 1 Réalisation des éléments obligatoires attendus dans le projet
Le but est de vérifier si les points suivants sont remplis (issus des éléments obligatoires de la check-list de réalisation du projet):
- Utilisation de gabarits pour les pages de consultation du front-office suivantes (3 pts):
    - consultation d'un [objet]                  
    - consultation de la liste des [objets] d'un [inventaire]                  
    - navigation d'un [inventaire] vers la consultation de ses [objets]
- Intégration d'une mise en forme CSS avec Bootstrap dans les gabarits Twig (1 pt)
- Ajout de l'entité [galerie] au modèle des données et de l'association M-N avec [objet] (1 pt)
- Ajout de fonctions CRUD au front-office pour [inventaire] (1 pt)
- Contextualisation de la création d'un [objet] en fonction de l'[inventaire] (1 pt)
- Ajout des utilisateurs au modèle de données et du lien utilisateur - membre (1 pt)
- Ajout de l'authentification (y compris documentation sur comptes et leurs rôles dans README) (1pt)

Voici les instructions à suivre pour procéder à l'évaluation de la partie front-end du projet:
- Lancer les server local avec la commande: symfony server:start
- Charger les fixtures avec la commande: ```make reload_fixtures``` (cette commande exécute un script qui effectue les opérations nécessaires, voir *Makefile*)
- Dans un navigateur web se rendre sur: https://localhost:8000/shelf
- Créer un compte et s'y connecter (ou utiliser un compte déjà existant dans les fixtures pour ne pas avoir à créer des galeries (**Shelf**), inventaires (**Cupboard**) et objets (**Shoe**))
- En étant connecté, il est possible de consulter un objet (**Shoe**) depuis la section *Shoes* sous la catégorie *Profile*
- En étant connecté, il est possible de consulter la liste des objets (**Shoe**) d'un inventaire (**Cupboard**) depuis la section *Cupboards* sous la catégorie *Profile*
- Lorsqu'on visualise un inventaire (**Cupboard**), cliquer sur un objet (**Shoe**) permet de le visualiser
- En visitant les différentes pages on peut admirer la mise en page absoument magnifique: iconne d'onglet et image de fond à couper le souffle 😜 (je reconnais que c'est parfois moins lisible qu'un fond blanc mais c'est globalement plus joli...pour compenser j'ai ajouté un fond transparent, voir *base.html.twig*, ligne 97)
- Dans l'onglet *Shelves*, on peut visualiser les galeries (**Shelf**) privées (une fois connecté) et publiques puis cliquer sur les liens pour naviguer entre objet (**Shoe**) et galerie (**Shelf**)
- En étant connecté, il est possible de créer un inventaire (**Cupboard**) en cliquant sur le boutton *Create new cupboard* depuis la section *Cupboards* sous la catégorie *Profile* et on peut modifier ou supprimer un inventaire (**Cupboard**) existant
- Lorsqu'on crée ou qu'on modifie un inventaire (**Cupboard**), il est possible de créer de nouveaux objets (**Shoe**) ou supprimer les objets (**Shoe**) qu'il contient
- Se déconnecter puis se connecter avec les identifiants *admin@localhost* et *admin* puis se rendre sur: https://localhost:8000/admin, on observe alors la présence des utilisateurs (**User**) et le lien avec les membres (**Member**)

## 2 Réalisation des éléments optionnels
Le but est de vérifier si les points suivants sont remplis (issus des éléments optionnels de la check-list de réalisation du projet):
- Protection de l'accès aux routes interdites aux utilisateurs non-authentifiées car réservées aux membres (1 pt)
- Protection de l'accès aux CRUD sur les données aux seuls propriétaires de ces données (1 pt)
- Ajout de la gestion de la mise en ligne d'images pour des photos dans les [objet] (1 pt)

Voici les instructions à suivre pour procéder à l'évaluation de la partie front-end facultative du projet:
- En étant déconnecté, essayer de se rendre dans l'onglet *Members* essayer de se rendre sur: https://localhost:8000/shelf/1/edit, on atterit non pas sur la page attendue mais sur la page de connection
- En étant connecté (utilisateur normal), se rendre dans l'onglet *Members* et esayer de supprimer un autre membre (**Member**) ou essayer de se rendre sur: https://localhost:8000/shelf/1/edit (si on n'est pas user_1), on atterit non pas sur la page attendue mais sur une page d'erreur
- Bien que la mise en page du site laisse penser qu'on peut ajouter des photos pour chaque entité (membre (**Member**), invetaires (**Cupboard**), galeries (**Shelf**)), dans l'état actuel de l'application seuls les objets (**Shoe**) peuvent réellement avoir une image: cette image peut être ajoutée/modifiée à la création de l'objet (**Shoe**) ou en le modifiant

## 3 Suppléments
Voici quelques suppléments d'information concernant le projet:
- Pour consulter l'interface de l'API, se rendre sur: https://localhost:8000/api
- En étant connecté comme administarteur (avec les identifiants *admin@localhost* et *admin*) l'interface EasyAdmin est accessible sur: https://localhost:8000/admin
- Par conception, les onglets *Shelves*, *Cupboards* et *Shoes* affichent uniquement les boutons et liens permettant de réaliser les actions autorisées pour l'utilisateur (**User**)
- En revanche, l'onglet *Members* affiche les boutons et liens de toutes les actions possibles mais l'utilisateur (**User**) pourra être redirigé vers la page de connection ou rencontrer des erreurs suivant le rôle qui lui a été attribué (par exemple un utilisateur peut essayer de supprimer son profil (**Member**) et affiche une erreur, dans ce cas il faudrait masquer le bouton delete quand l'utilisateur (**User**) n'est pas administrateur avec ```{% if is_granted('ROLE_USER') %}``` dans *templates/member/show.html.twig* à la ligne 174)
- Il est possible de marquer des galeries (**Shelf**) en étant connecté, les galeries (**Shelf**) marquées apparaissent dans une section à part sur la page dans l'onglet *Shelves*
- On remarquera les interfaces dynamiques dans l'onglet *Shelves* et dans les pages de modification des inventaires (**Cupboard**) et des galeries (**Shelf**)
- Les utilisateurs (**User**) ayant le rôle ADMIN peuvent modifier les données de tous les utilisateurs

## 4 Problèmes

Si une action décrite dans ce fichier (ou dans le fichier *README.md*) ne fonctionne pas veuillez me contacter ou contacter le professeur.

Pour toute autre question veuillez me contacter.

Contact: fabien.allemand@telecom-sudparis.eu