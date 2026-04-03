<?php
require_once __DIR__ . "/Element.php";

class Tag extends Element {
    public function __construct($id, $nom) {
        parent::__construct($id, $nom);
    }

    // Surcharge de afficher() - polymorphisme
    public function afficher() {
        return "Tag : {$this->nom}";
    }

    // Récupérer tous les tags
    public static function getAll($pdo) {
        $stmt = $pdo->query("SELECT * FROM tag ORDER BY nom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter un tag
    public static function create($pdo, $nom) {
        $stmt = $pdo->prepare("INSERT INTO tag (nom) VALUES (:nom)");
        $stmt->execute([':nom' => $nom]);
    }

    // Supprimer un tag
    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM tag WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    // Récupérer les tags d'une recette
    public static function getByRecette($pdo, $recette_id) {
        $stmt = $pdo->prepare("
            SELECT t.id, t.nom
            FROM tag t
            JOIN recette_tag rt ON t.id = rt.tag_id
            WHERE rt.recette_id = :rid
            ORDER BY t.nom
        ");
        $stmt->execute([':rid' => $recette_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
