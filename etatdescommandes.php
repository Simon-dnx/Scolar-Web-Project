<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<?php
    $pdo = new PDO("pgsql:host=localhost;dbname=etd","uapv2102630","Ck1ehp");

    // Requête pour obtenir le tableau de tous les produits classés par le nombre de ventes
    $sql = "SELECT products.product_id, products.product_name, SUM(order_items.quantity) AS nbvente
            FROM orders
            JOIN order_items ON orders.order_id = order_items.order_id
            JOIN products ON order_items.product_id = products.product_id
            WHERE orders.order_status = 4 
            GROUP BY products.product_id
            ORDER BY nbvente DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,"Product",array(null,null,null,null,null));
    $stmt->execute();
    // Affichage du tableau de produits
    echo "<table class='w3-table w3-striped'>";
    echo "<thead><tr><th>Produit</th><th>Total des ventes</th></tr></thead>";
    echo "<tbody>";
    while ($product =$stmt->fetch()) {
        ?>
        <tr><td><a href="<?php echo $product->getProductLien();?>" ><?php echo $product->getProductName();?></td></a><td><?php echo $product->getProductNbVente();?></td></tr>
        <?php
        
    }
    echo "</tbody>";
    echo "</table>";
?>