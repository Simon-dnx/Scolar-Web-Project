
<?php
// Connexion à la base de données
try {
    $pdo = new PDO("pgsql:host=localhost;dbname=confidential","Confidential","Confidential");
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}



// Récupération des informations du client
$client_id = $_GET['client'];
$stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_id = ?"); // '?' signifie argument de la requete
$stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,"Customer", array(null,null,null,null,null,null,null,null,null));
$stmt->execute([$client_id]); // dans ce cas la c'est client_id
$client = $stmt->fetch();


// Récupération des commandes du client, triées par statut
$stmt2 = $pdo->prepare("SELECT *
    FROM orders
    WHERE customer_id = ?
    ORDER BY order_status
");
$stmt2->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,"Order",array(null,null,null,null,null,null,null,null));
$stmt2->execute([$client_id]);
// Affichage du tableau des commandes
echo "<h1 class='w3-center'>Commandes de ".$client->getLastName()." ".$client->getFirstName()."</h1>";
echo "<div class='w3-container'><table class='w3-table w3-striped'>";
echo "<tr><th>ID</th><th>Statut</th><th>Magasin</th><th>Date de commande</th><th>Date requise</th><th>Date d'expédition</th><th class='details-produits'>Produits</th></tr>";

while ($order = $stmt2->fetch()) {
    echo "<tr class='yel'>";
    echo "<td>".$order->getOrderId()."</td>";
    echo "<td>";
    switch ($order->getOrderStatus()) {
        case 1:
            echo "En attente";
            break;
        case 2:
            echo "En cours";
            break;
        case 3:
            echo "Rejetée";
            break;
        case 4:
            echo "Terminée";
            break;
    }
    echo "</td>";
    echo "<td>".$order->getStoreName()."</td>"; // Fonction qui me récupère le nom du mangasin grâce à l'id
    echo "<td>".$order->getOrderDate()."</td>";
    echo "<td>".$order->getRequiredDate()."</td>";
    echo "<td>".$order->getShippedDate()."</td>";

    // Récupération des produits de la commande, triés par catégorie
    $stmt = $pdo->prepare("
        SELECT p.product_id, p.product_name , t.category_name
        FROM order_items oi
        JOIN products p ON oi.product_id = p.product_id
        JOIN types t ON p.category_id = t.category_id
        WHERE oi.order_id = ?
        ORDER BY t.category_name ASC
    ");
    $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,"Product",array(null,null,null,null,null));
    $stmt->execute([$order->getOrderId()]);
    

    echo "<td class='details-produits'>";
    while ($product = $stmt->fetch()) {
        echo $product->getProductName()." (".$product->getProductCategory().")<br>";
    }
    echo "</td>";

    echo "</tr>";
}

echo "</table></div>";

// Bouton de retour vers la liste des clients

echo"<a href='index.php'><button class='w3-lime w3-button'>Enlever la liste des commandes</button></a>";
?>
<button class="w3-button w3-black" id="toggle-details">Afficher/Cacher détails produits</button></div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    // Cacher les détails produits au démarrage de la page
    $('.details-produits').hide();

    // Ajouter un gestionnaire d'événements au clic sur le bouton
    $('#toggle-details').click(function() {
      // Afficher ou cacher les détails produits
      $('.details-produits').toggle();
    });
  });
  $(document).ready(function(){
        // Lorsque la souris entre dans une ligne
        $('.yel').mouseenter(function(){
            $(this).addClass('w3-yellow');
        });
        // Lorsque la souris sort d'une ligne
        $('.yel').mouseleave(function(){
            $(this).removeClass('w3-yellow');
        });
    });
</script>
