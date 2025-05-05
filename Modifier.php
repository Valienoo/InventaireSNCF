<?php

$host = 'localhost';
$dbname = 'inventaire';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupérer l'ID du produit à modifier depuis l'URL ou la requête
$productID = $_GET['id'] ?? null;
$produit = null;

if ($productID) {
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
    $stmt->execute([$productID]);
    $produit = $stmt->fetch();
}

// Enregistrer les modifications dans la base de données
if (isset($_POST['modifier'])) {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];

    $stmt = $pdo->prepare("UPDATE produits SET nom = ?, description = ?, prix = ? WHERE id = ?");
    $stmt->execute([$nom, $description, $prix, $productID]);

    echo "Produit modifié avec succès!";
    header("Location: affichage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Produit</title>
    <link rel="stylesheet" href="modifier.css">
</head>
<body>

<center>
    <div class="page">
        <br>
        <img src="pictures/logo.png" alt="Logo">
        <h2>Modifier un Produit</h2>
        <form id="modifForm" action="" method="post">
            <div class="input-container">
                <input maxlength="25" type="text" name="nom" value="<?php echo htmlspecialchars($produit['nom']); ?>" placeholder="Nom du produit" required>
                <div class="clear-animation" id="clear-nom"></div>
            </div>
            <div class="input-container">
                <textarea maxlength="75" name="description" placeholder="Description" required><?php echo htmlspecialchars($produit['description']); ?></textarea>
                <div class="clear-animation" id="clear-description"></div>
            </div>
            <div class="input-container">
                <input maxlength="5" type="number" name="prix" value="<?php echo htmlspecialchars($produit['prix']); ?>" placeholder="Prix" required>
            </div>
            <!-- Boutons de validation et annulation -->
            <button type="submit" name="modifier" class="modifier-btn">Modifier</button>
        </form>
        <!-- Bouton Annuler en dessous -->
        <a href="affichage.php"><button type="button" class="annuler-btn">Annuler</button></a>
    </div>
</center>

</body>
</html>
