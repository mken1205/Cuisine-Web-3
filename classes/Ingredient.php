<?php
require_once __DIR__ . "/Element.php";

class Ingredient extends Element {
    private $quantite;

    public function __construct($id, $nom, $quantite = '') {
        parent::__construct($id, $nom);
        $this->quantite = $quantite;
    }

    public function getQuantite() { return $this->quantite; }

    // Surcharge de afficher() - polymorphisme
    public function afficher() {
        return "Ingrédient : {$this->nom} ({$this->quantite})";
    }

    // Récupérer tous les ingrédients
    public static function getAll($pdo) {
        $stmt = $pdo->query("SELECT * FROM ingredient ORDER BY nom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter un ingrédient
    public static function create($pdo, $nom) {
        $stmt = $pdo->prepare("INSERT INTO ingredient (nom) VALUES (:nom)");
        $stmt->execute([':nom' => $nom]);
    }

    // Supprimer un ingrédient
    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM ingredient WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    // Récupérer les ingrédients d'une recette avec leurs quantités
    public static function getByRecette($pdo, $recette_id) {
        $stmt = $pdo->prepare("
            SELECT i.id, i.nom, ri.quantite
            FROM ingredient i
            JOIN recette_ingredient ri ON i.id = ri.ingredient_id
            WHERE ri.recette_id = :rid
            ORDER BY i.nom
        ");
        $stmt->execute([':rid' => $recette_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
