<?php
// Classe de base dont héritent Recette, Ingredient et Tag
class Element {
    protected $id;
    protected $nom;

    public function __construct($id, $nom) {
        $this->id  = $id;
        $this->nom = $nom;
    }

    public function getId()  { return $this->id; }
    public function getNom() { return $this->nom; }

    // Méthode surchargée dans les classes enfants (polymorphisme)
    public function afficher() {
        return "Element : " . $this->nom;
    }

    public function __toString() {
        return $this->afficher();
    }
}
?>
