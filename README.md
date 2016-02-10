Cocktails
=======

Ready to be starred on [Github](https://github.com/Blackrush/cocktailsphp)!

### How to Set Up

```bash
$ make setup
```

### How to Run

```bash
$ make run
```

### I'm done testing, keep this crap away from my computer

```bash
$ make setdown
```

### Assignment given on February 9th within CS classes in University of Le Havre.

On utilise les tables permettant de modéliser des recettes de cocktails vues au premier semestre. Un schéma Entité/Association et un script SQL est fourni dans l'archive coktails_SQL.zip

1. Réaliser une classe BoissonMetier construite de la même façon que la classe LivreMetier vue en cours.
2. Ajouter une méthode statique getAll() qui retourne un tableau d'instance de BoissonMetier ; cette méthode utilisera la méthode PDOStatement::fetchAll
3. Ajouter une méthode statique getArrayIterator() qui retourne un ArrayIterator sur les BoissonMetiers
4. Ajouter une méthode statique getLimitIteraror($debut, $taille) qui retourne un LimitIterator sur les BoissonMetiers
5. Réaliser une page qui présente les boissons de façon paginée (8 boissons par page). L'intégration des pages dans un framework (Foundation, Bootstrap, ...) est indispensable.
