<?php
class Personnage
{

    // attributs
    private $_id, $_degats, $_nom;

    // Trois constantes de classe renvoyées par la méthode frapper()
    // si on se frappe soi-même
    const CEST_MOI = 1;
    // si on a tué le personnage en le frappant
    const PERSONNAGE_TUE = 2;
    // si on a bien frappé le personnage
    const PERSONNAGE_FRAPPE = 3;

    public function __construct(array $donnees) {
        $this->hydrate($donnees);
      }

    public function hydrate(array $donnees) {
        foreach ($donnees as $key => $value) {
          // ucfirst : met le premier caractère en majuscule
          $method = 'set'.ucfirst($key); 
          
          if (method_exists($this, $method)) {  
          // method_exists : vérifie si la méthode existe (ici pour l'objet $this)
            $this->$method($value);
          }
        }
      }

      public function nomValide() {
        return !empty($this -> _nom);
      }

    public function getDegats()
    {
        return $this->_degats;
    }
    public function getId()
    {
        return $this->_id;
    }
    public function getNom()
    {
        return $this->_nom;
    }
    public function setDegats($degats)
    {
        if ($degats >= 0 && $degats <= 100) {
            $this->_degats = $degats;
        }
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function setNom($nom)
    {
        $this->_nom = $nom;
    }

    public function frapper(Personnage $perso)
    {
        // renvoie la constante de classe CEST_MOI

        if ($this->_id == $perso->_id){
            return self::CEST_MOI;
        }
        return $perso->recevoirDegats();
    }

    public function recevoirDegats()
    {
        // renvoie les constantes de classe PERSONNAGE_TUE ou PERSONNAGE_FRAPPE

        $this->_degats +=5;

        if ($this->_degats >= 100){
            return self::PERSONNAGE_TUE;
        }
        return self::PERSONNAGE_FRAPPE;
    }
}
