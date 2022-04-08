<?php

class PersonnagesManager
{
    private $_db; // Instance de PDO

    public function __construct($db)
    {
        $this->setDb($db);
    }


    public function add(Personnage $perso)
    {
        // Préparation de la requête d'insertion.
        // Assignation des valeurs pour le nom du personnage.
        // Exécution de la requête.
        $requete = $this->_db->prepare('INSERT INTO personnages(nom) VALUES (?)');
        $requete->execute([$perso->getNom()]);

        // Hydratation du personnage passé en paramètre avec assignation de son identifiant et des dégâts initiaux (= 0).

        $perso->hydrate([
            'id' => $this->_db->lastInsertId(),
            'degats' => 0
        ]);
    }

    public function count()
    {
        // Exécute une requête COUNT() et retourne le nombre de résultats retourné.
        return $this->_db->query('SELECT COUNT(*) FROM personnages')->fetchColumn();
    }

    public function delete(Personnage $perso)
    {
        // Exécute une requête de type DELETE.
        $requete = $this->_db->prepare('DELETE FROM personnages WHERE id = ?');
        $requete->execute([$perso->getId()]);
    }

    public function exists($info)
    {
        // Si le paramètre est un entier, c'est qu'on a fourni un identifiant.
        // On exécute alors une requête COUNT() avec une clause WHERE, et on retourne un boolean.
        if (is_int($info)) {
            return (bool) $this->_db->query('SELECT COUNT(*) FROM personnages WHERE id = ' . $info)->fetchColumn();
        }

        // Sinon c'est qu'on a passé un nom.
        // Exécution d'une requête COUNT() avec une clause WHERE, et retourne un boolean.
        $requete = $this->_db->prepare('SELECT COUNT(*) FROM personnages WHERE nom = ? ');
        $requete->execute([$info]);
        return (bool) $requete->fetchColumn();
    }

    public function get($info)
    {
        // Si le paramètre est un entier, on veut récupérer le personnage avec son identifiant.
        // Exécute une requête de type SELECT avec une clause WHERE, et retourne un objet Personnage.
        if (is_int($info)) {
            $requete = $this->_db->prepare('SELECT * FROM personnages WHERE id = ?');
            $requete->execute([$info]);
            $donnees = $requete->fetch(PDO::FETCH_ASSOC);
            return new Personnage($donnees);
        } else {
            // Sinon, on veut récupérer le personnage avec son nom.
            // Exécute une requête de type SELECT avec une clause WHERE, et retourne un objet Personnage.
            $requete = $this->_db->prepare('SELECT * FROM personnages WHERE nom = ?');
            $requete->execute([$info]);
            $donnees = $requete->fetch(PDO::FETCH_ASSOC);
            return new Personnage($donnees);
        }
    }

    public function getList($nom)
    {
        // Retourne la liste des personnages dont le nom n'est pas $nom.
        // Le résultat sera un tableau d'instances de Personnage.
        $persos = [];

        $requete = $this->_db->prepare('SELECT * FROM personnages WHERE nom != ?');
        $requete->execute([$nom]);

        while ($donnees = $requete->fetch(PDO::FETCH_ASSOC)){
            $persos[] = new Personnage($donnees);
        }

        return $persos;
    }

    public function update(Personnage $perso)
    {
        // Prépare une requête de type UPDATE.
        // Assignation des valeurs à la requête.
        // Exécution de la requête.
        $requete = $this->_db->prepare('UPDATE personnages SET degats = ? WHERE id = ?');
        $requete->execute([$perso->getDegats(), $perso->getId()]);
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}
