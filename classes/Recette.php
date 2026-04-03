<?php
require_once __DIR__ . "/Element.php";

class Recette extends Element {
    private $description;
    private $photo;
    private $created_at;

    public function __construct($id, $nom, $description = '', $photo = null, $created_at = '') {
        parent::__construct($id, $nom);
        $this->description = $description;
        $this->photo       = $photo;
        $this->created_at  = $created_at;
    }

    public function getDescription() { return $this->description; }
    public function getPhoto()        { return $this->photo; }
    public function getCreatedAt()    { return $this->created_at; }

    // Surcharge de afficher() - polymorphisme
    public function afficher() {
        return "Recette : {$this->nom} - {$this->description}";
    }

    // Récupérer toutes les recettes
    public static function getAll($pdo) {
        $stmt = $pdo->query("SELECT * FROM recette ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer une recette par son id
    public static function getById($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM recette WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Créer une recette, retourne le nouvel id
    public static function create($pdo, $titre, $description, $photo) {
        $stmt = $pdo->prepare("
            INSERT INTO recette (titre, description, photo)
            VALUES (:titre, :desc, :photo)
        ");
        $stmt->execute([':titre' => $titre, ':desc' => $description, ':photo' => $photo]);
        return $pdo->lastInsertId();
    }

    // Modifier une recette
    public static function update($pdo, $id, $titre, $description, $photo) {
        $stmt = $pdo->prepare("
            UPDATE recette SET titre = :titre, description = :desc, photo = :photo
            WHERE id = :id
        ");
        $stmt->execute([':titre' => $titre, ':desc' => $description, ':photo' => $photo, ':id' => $id]);
    }

    // Supprimer une recette (les liaisons sont supprimées par CASCADE)
    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM recette WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    // Recherche par mot-clé dans le titre ou la description
    public static function rechercher($pdo, $motcle) {
        $stmt = $pdo->prepare("
            SELECT * FROM recette
            WHERE titre LIKE :mot OR description LIKE :mot
            ORDER BY created_at DESC
        ");
        $stmt->execute([':mot' => '%' . $motcle . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter un tag à une recette
    public static function addTag($pdo, $recette_id, $tag_id) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO recette_tag (recette_id, tag_id) VALUES (:rid, :tid)");
        $stmt->execute([':rid' => $recette_id, ':tid' => $tag_id]);
    }

    // Supprimer tous les tags d'une recette (utile pour la modification)
    public static function clearTags($pdo, $recette_id) {
        $stmt = $pdo->prepare("DELETE FROM recette_tag WHERE recette_id = :rid");
        $stmt->execute([':rid' => $recette_id]);
    }

    // Ajouter un ingrédient à une recette
    public static function addIngredient($pdo, $recette_id, $ingredient_id, $quantite) {
        $stmt = $pdo->prepare("
            INSERT INTO recette_ingredient (recette_id, ingredient_id, quantite)
            VALUES (:rid, :iid, :qte)
        ");
        $stmt->execute([':rid' => $recette_id, ':iid' => $ingredient_id, ':qte' => $quantite]);
    }

    // Supprimer tous les ingrédients d'une recette (utile pour la modification)
    public static function clearIngredients($pdo, $recette_id) {
        $stmt = $pdo->prepare("DELETE FROM recette_ingredient WHERE recette_id = :rid");
        $stmt->execute([':rid' => $recette_id]);
    }
}
?>
