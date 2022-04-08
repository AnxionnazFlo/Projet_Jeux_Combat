# Projet Jeux_Combat

## 1. Contexte de l'évaluation 

Module de formation PHP Programmation Orientée Objet  
  
Faire une application mono-page sur laquelle chaque visiteur pourra créer un personnage avec lequel il pourra frapper d'autres personnages.  
L'application doit être fonctionnelle, le front ne sera pas noté.  
  
Points techniques que l'on va mettre en pratique :  
  
    * Les attributs et méthodes ;  
    * l'instanciation de la classe ;  
    * les constantes de classe ;  
    * et surtout, tout ce qui touche à la manipulation de données stockées.  

#### 1.1 Fonctionnalitées désirées :
  
- Le personnage frappé recevra des dégâts.
- Un personnage est défini selon 2 caractéristiques :  
    * Son nom (unique).  
    * Ses dégâts.  
- Les dégâts d'un personnage sont compris entre 0 et 100.  
- Au début, il a 0 de dégât. 
- Chaque coup qui lui sera porté lui fera prendre 5 points de dégâts.
- Une fois arrivé à 100 points de dégâts, le personnage est mort (on le supprimera alors de la BDD).  


#### 1.2 Contrainte technique : 

- Toute la page doit être gérée en PHP POO


## 2. Environnement technique

- HTML 5
- PHP POO
- Serveur local XAMPP
- MySQL DB
- Pas de Design Patern pour ce petit projet



## 3. Procédure de mise en place en local

- Cloner le fichier sur votre ordinateur avec  
  `git clone https://github.com/AnxionnazFlo/Projet_Jeux_Combat.git`

- Installer le projet dans le dossier htdocs de XAMPP (www sous WAMP)  

- Via PHPmyAdmin, importez le fichier jeuxCombat.sql dans votre BDD  

- Vérifier l'utilisateur et mot de passe dans le fichier test.php (ligne 21)  

- Créer au moins deux personnages pour pouvoir faire fonctionner l'application

- Lancer test.php 

- N'oubliez pas que le front n'était pas le but de l'exercice :)

- Tout devrais fonctionner à présent

#### Have fun



 



