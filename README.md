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

**OU** 1 à 2 en oneshot :
```
git clone git@github.com:BourGuy64/LP-PHP.git && git update-index --assume-unchanged src/conf/conf.ini
```
3. Créez la base de données avec le fichier `/LP-PHP/src/sql/database.sql`.

4. Créez le fichier de conf.ini dans le répertoire src/conf/ avec le template suivant, en ajustant les valeurs :
```
db_driver=mysql
host=localhost
dbname=database
db_user=user
db_password=password
db_charset=utf8
db_collation=utf8_unicode_ci
prefix=
```


## Minimiser un fichier (.min)
### Requis
- NPM package [minify](https://www.npmjs.com/package/minify)
### Utilisation
- Minimiser un fichier :
```
minify input.css > output.min.css
```
```
minify input.js > output.min.js
```
- Minimiser tous les fichiers d'un dossier :
```
for file in *.css;
do minify "$file" > "${file%.css}.min.css";
done
```
```
for file in *.js;
do minify "$file" > "${file%.js}.min.js";
done
```
