# RatzSlayer3
Repository du projet de Licence Pro CIASIE en PHP.

## Documents
[Sujet](https://arche.univ-lorraine.fr/pluginfile.php/1834821/mod_resource/content/1/LP2%20-%20Projet%20Fil%20Rouge.pdf)

## Requis
- Apache
- MySQL
- PHP
- Composer


## Installation
1. Clonez le repository à la racine du serveur apache en utilisant la commande suivante :
```
git clone git@github.com:BourGuy64/LP-PHP.git
```
2. Empechez le suivie du fichier conf.ini par git :
```
git update-index --assume-unchanged src/conf/conf.ini
```

**OU** 1 à 2 en oneshot :
```
git clone git@github.com:BourGuy64/LP-PHP.git && git update-index --assume-unchanged src/conf/conf.ini
```
3. Créez la base de données avec le fichier `/LP-PHP/src/sql/database.sql`.
4. Modifier le nom de la base de données et les identifiants de connexion (lignes commentées) dans le fichier `/LP-PHP/src/conf/conf.ini` et décommentez les lignes.


## Minimiser un fichier (.min)
### Requis
- NPM package [minify](https://www.npmjs.com/package/minify)
### Utilisation
- Minimiser un fichier :
```
minify input.css > output.min.css
```
- Minimiser tous les fichiers d'un dossier :
```
for file in src/css/*.css;
do minify "$file" > "${file%.css}.min.css";
done
```

## Taches
- [ ] 1. Etape 1
- [ ] 2. Etape 2
- [ ] 3. Etape 3
