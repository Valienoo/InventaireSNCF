<?php
// Vérification si l'ID des produits à supprimer a été envoyé
if (isset($_POST['ids'])) {
    // Connexion à la base de données
    $host = 'localhost';
    $dbname = 'inventaire';
    $username = 'root';
    $password = '';
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupérer les IDs des produits à supprimer
        $ids = json_decode($_POST['ids'], true); // Décoder le JSON envoyé

        // Préparer la requête SQL pour supprimer les produits
        $placeholders = implode(',', array_fill(0, count($ids), '?')); // Crée une liste de placeholders pour les IDs
        $sql = "DELETE FROM produits WHERE id IN ($placeholders)";
        $stmt = $pdo->prepare($sql);

        // Exécuter la requête en passant les IDs
        $stmt->execute($ids);

        // Retourner une réponse de succès
        echo 'Produits supprimés avec succès!';
    } catch (PDOException $e) {
        echo 'Erreur de connexion ou d\'exécution : ' . $e->getMessage();
    }
} else {
    echo 'Aucun produit sélectionné.';
}
?>
