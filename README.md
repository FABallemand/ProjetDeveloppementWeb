# 🛜 Projet Developpement Web: Trail Running Shoes

## 👟 Description

Trail Running Shoes (TRS) is a community based website rencensing relevant data about trail running shoes.  
Athletes can share feedbacks, feelings or technical details about the shoes they own (and store in a cupboard) and browse the thoughts of other trail runners about other running sneackers.  
All athletes can easily display their favorite (or worst) shoes on a special shelf.

|           |       Trail Runner        |
|-----------|---------------------------|
| Object    |  Trail Shoes (**Shoe**)   |
| Inventory |  Cupboard (**Cupboard**)  |
| Galerie   |     Shelf (**Shelf**)     |

## 📝 Step-by-step Description

### Step 1: New Symfony Project
- [x] Read technical specifications
- [x] Choose domain
- [x] Initialise working directory
- [x] Create new Symfony project

### Step 2: First Entities
- [x] Prepare to add first entities
- [x] Configure database
- [x] Add support for data-fixtures
- [x] Add object (**Shoe**) and inventory (**Cupboard**) entities to data model
- [x] Add test data

### Step 3: EasyAdmin interface & CRUD controllers
- [ ] Add main CRUD controller
- [ ] Add inventory (**Cupboard**) CRUD controller
- [ ] Add object (**Shoe**) CRUD controller

### Step 4: Add Member Entity
- [ ] Add member (**Runner**) entity
- [ ] Add member-inventory link

### Step 5: First Public Pages
- [ ] Add inventory (**Cupboard**) controller
- [ ] Add method to display list inventories (**Cupboard**)
- [ ] Add method to browse an inventory (**Cupboard**)
- [ ] Add link to browse in the displayed list of inventories (**Cupboard**)

### Step 6: OneToMany Relations Administration Board
- [ ] Add administration table for OneToMany relations

### Step 7: Administration Board Improvements
- [ ] ...

### Step 8: Current Entities Administration Board
- [ ] ...

### Step 9: Pages Gabarits & Front-Office Display
- [ ] Add display of an object (**Shoe**) inside an inventory (**Cupboard**) in the front-office

### Step 10: Back-Office Display
- [ ] Add page to display inventory (**Cupboard**)
- [ ] Add list of the entities in the page

### Step 11: CSS
- [ ] Download and integrate Bootstrp in templates
- [ ] Add menus for Bootstrap

### Step 12: Add Galery Entity
- [ ] Add galery (**Shelf**) entity

### Step 13: Galery Administration Board
- [ ] Add galery (**Shelf**) entities to administration board

### Step 14: Galery Front-Office CRUD Controller
- [ ] Add new CRUD controller for galeries (**Shelf**)
- [ ] Improve display

### Step 15: Front-Office Symfony Controllers Modifications Methods
- [ ] ...

### Step 16: Galery Browsing
- [ ] ...

### Step 17: Acces Contextualisation & Permited Operations Restrictions
- [ ] Add CRUD controller for member entity
- [ ] Contextualise inventory (**Cupboard**) creation
- [ ] Contextualise object (**Shoe**) creation
  
### Step 18: Galerie's Objects Management
- [ ] Contextualise galery (**Shelf**) creation
- [ ] Contextualise public galeries (**Shelf**) display
- [ ] Contextualise adding an object (**Shoe**) to a galery (**Shelf**)

### Step 19: Authentification
- [ ] Add authentification

### Step 20: Acces Control
- [ ] Read only access for members
- [ ] Consulatation only access for owner and administrator
- [ ] Write access to data owner
- [ ] Access to non-public galeries (**Shelf**)
- [ ] Access to objects (**Shoe**) inside non-public galeries (**Shelf**)

### Step 21: Data Loading Contextualisation
- [ ] Galeries (**Shelf**) display
- [ ] Objects (**Shoe**) and inventories (**Cupboard**) display

### Step 22: Data Removal
- [ ] Remove objects (**Shoe**) from deleted inventories (**Cupboard**)
- [ ] ManyToMany relations management

### Step 23: Have fun !!

## ⌨️ Useful Commands (for developpement purpose...)

1. Create Database
> symfony console doctrine:database:create
> symfony console doctrine:schema:create --dump-sql
> symfony console doctrine:schema:create
2. Update Database
> symfony console doctrine:schema:update
3. Delete Database
> symfony console doctrine:database:drop --force
1. Load Data Fixtures
> symfony console doctrine:fixtures:load -n

## 👤 Author
- Fabien ALLEMAND