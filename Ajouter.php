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
?>
<link rel="stylesheet" href="accueil.css">

<?php

if (isset($_POST['ajouter'])) {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];

    $stmt = $pdo->prepare("INSERT INTO produits (nom, description, prix) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $description, $prix]);

    echo "Produit ajouté avec succès!";

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Produits</title>
    <link rel="stylesheet" href="Add.css">
    <script src="ajouter.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.4/lottie.min.js"></script>

</head>
<body>

    <div class="menu-button" id="menuButton"></div>

    <div class="side-menu" id="sideMenu">
        <br>
        <br>
        <br>
        <h2>Menu</h2>
        <ul>
            <li><a href="ajouter.php" style="color:white;">Ajouter un produit</a></li>
            <li><a href="affichage.php" style="color:white;">Voir le stock</a></li>
        </ul>
    </div>

    <center>
        <div class="page">
            <br>
            <img src="pictures/logo.png" alt="Logo">
            <br>
            <br>
            <h2>Ajouter un Produit</h2>
            <br>
            <form id="ajoutForm" action="" method="post">
                <div class="input-container">
                    <input maxlength="25" type="text" name="nom" placeholder="Nom du produit" required>
                    <div class="clear-animation" id="clear-nom"></div>
                </div>        
                <br>
                <div class="input-container">
                    <textarea maxlength="75" name="description" placeholder="Description"></textarea>
                    <div class="clear-animation" id="clear-description"></div>
                </div>        
                <br>
                <div class="input-container">
                    <input maxlength="5" type="number" name="prix" placeholder="Prix" required>
                    
                </div>         
                <br>        

                <button type="submit" name="ajouter" class="ajouter-btn">Ajouter</button>
            </form>
        </div>
    </center>

    <script>
        const menuButton = lottie.loadAnimation({
            container: document.getElementById('menuButton'),
            path: 'animations/OpenMenu.json',
            renderer: 'svg',
            loop: false,
            autoplay: false
        });

        let menuOpen = false;

        document.getElementById('menuButton').addEventListener('click', function() {
            menuButton.play();

            menuOpen = !menuOpen;

            const sideMenu = document.getElementById('sideMenu');
            if (menuOpen) {
                sideMenu.style.left = '0';
            } else {
                sideMenu.style.left = '-350px';
            }
        });

        menuButton.addEventListener('complete', () => {
            menuButton.goToAndStop(0, true);
        });
    </script>

</body>
</html>
