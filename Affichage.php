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
// Afficher les produits
$stmt = $pdo->query("SELECT * FROM produits");
$produits = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Produits</title>
    <link rel="stylesheet" href="Produits.css">
    <script src="afficher.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.4/lottie.min.js"></script>
</head>
<body>

    <div class="menu-button" id="menuButton"></div>

    <div class="side-menu" id="sideMenu">
        <br><br><br>
        <h2>Menu</h2>
        <ul>
            <li><a href="ajouter.php" style="color:white;">Ajouter un produit</a></li><br>
            <li><a href="affichage.php" style="color:white;">Voir le stock</a></li>
        </ul>
    </div>

<div class="page">
    <br>
    <img src="pictures/logo.png" alt="Logo">
    <center><h1>Liste des Produits</h1></center>
    <br><br><br><br>

    <table class="Tableau">
        <tr>
            <th>Selection</th>
            <th>ID</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Prix</th>
        </tr>
        <?php foreach ($produits as $produits): ?>
            <tr>
                <td>
                    <div class="check-animation-container" id="check-<?php echo $produitx['id']; ?>" style="width: 30px; height: 30px; cursor: pointer;">
                        <div class="check-icon" data-id="<?php echo $produitx['id']; ?>"></div>
                    </div>
                </td>
                <td><?php echo htmlspecialchars($produits['Id']); ?></td>
                <td><?php echo htmlspecialchars($produits['nom']); ?></td>
                <td><?php echo htmlspecialchars($produits['description']); ?></td>
                <td><?php echo htmlspecialchars($produits['prix']); ?> €</td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="action-buttons">
        <div id="binButton" style="width: 40px; height: 40px; cursor: pointer; display: inline-block;"></div>
        <div id="editButton" style="width: 40px; height: 40px; cursor: pointer; display: inline-block;"></div>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.4/lottie.min.js"></script>
<script>
    // Initialiser les animations Lottie pour chaque check
    const checkIcons = document.querySelectorAll('.check-icon');

    checkIcons.forEach(function (icon) {
        const id = icon.getAttribute('data-id');

        const animation = lottie.loadAnimation({
            container: icon,
            path: 'animations/check.json',
            renderer: 'svg',
            loop: false,
            autoplay: false
        });

        let isChecked = false;

        icon.addEventListener('click', function () {
            if (!isChecked) {
                animation.play();
                isChecked = true;
                icon.classList.add('checked');
            } else {
                animation.goToAndStop(0, true);
                isChecked = false;
                icon.classList.remove('checked');
            }
        });
    });

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

    const binButton = lottie.loadAnimation({
        container: document.getElementById('binButton'),
        path: 'animations/bin.json',
        renderer: 'svg',
        loop: false,
        autoplay: false
    });

    const editButton = lottie.loadAnimation({
        container: document.getElementById('editButton'),
        path: 'animations/edit.json',
        renderer: 'svg',
        loop: false,
        autoplay: false
    });

    //hover
    document.getElementById('binButton').addEventListener('mouseenter', function () {
        binButton.goToAndStop(0, true);
        binButton.play();
    });

    document.getElementById('editButton').addEventListener('mouseenter', function () {
        editButton.goToAndStop(0, true);
        editButton.play();
    });

    // Supprimer produits cochés
    document.getElementById('binButton').addEventListener('click', function () {
        const checkedProducts = [];

        checkIcons.forEach(function (icon) {
            if (icon.classList.contains('checked')) {
                const productId = icon.getAttribute('data-id');
                checkedProducts.push(productId);
            }
        });

        if (checkedProducts.length > 0) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'supprimer_produits.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert('Produits supprimés avec succès!');
                    location.reload();
                }
            };
            xhr.send('ids=' + JSON.stringify(checkedProducts));
        } else {
            alert('Aucun produit sélectionné pour suppression.');
        }
    });


document.getElementById('editButton').addEventListener('click', function () {
    const checkedProducts = [];

    // Récupérer les cases cochées
    checkIcons.forEach(function (icon) {
        if (icon.classList.contains('checked')) {
            const productId = icon.getAttribute('data-id');
            checkedProducts.push(productId);
        }
    });

    if (checkedProducts.length === 1) {
        // Redirection vers la page de modification avec l'ID du produit
        window.location.href = 'Modifier.php?id=' + checkedProducts[0];
    } else if (checkedProducts.length === 0) {
        alert('Sélectionnez un produit à modifier.');
    } else {
        alert('Veuillez ne sélectionner qu’un seul produit à modifier.');
    }
});

</script>

</body>
</html>
