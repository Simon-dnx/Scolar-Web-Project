<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<?php
    include "objet.php";
    session_start();
    if($_SESSION['admin']==true){
        
        $pdo = new PDO("pgsql:host=localhost;dbname=etd","uapv2102630","Ck1ehp");
        $product_id;
        if(isset($_GET['product_id'])){
            $product_id = $_GET['product_id'];
        }
        else{
            $product_id=0;
        }
        ?>
        <html><body>
        <header class="w3-light-green w3-container"><br>
        <?php
        echo "<h2 class='w3-center'>Voici la page du produit d'id ".$product_id."</h2></header>";
        // Requête pour obtenir la liste des magasins et le nombre de commandes pour le produit demandé
        $sql = "SELECT stores.store_id, stores.store_name, COUNT(DISTINCT orders.order_id) AS total_orders
        FROM orders
        JOIN order_items ON orders.order_id = order_items.order_id
        JOIN stores ON orders.store_id = stores.store_id
        JOIN products ON order_items.product_id = products.product_id
        WHERE products.product_id = ".$product_id."
        GROUP BY stores.store_id";
        $stmt = $pdo->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,"Store",array(null,null,null));
        $stmt->execute();
        
        
        // Affichage de la liste des magasins et le nombre de commandes pour le produit demandé
        echo "<div class='w3-responsive'>";
        echo "<table class='w3-table w3-striped w3-row'>";
        echo "<thead><tr><th>Magasin</th><th>Nombre de commandes</th><th>Commandes en Cours</th><th >Stock Global de ce produit</th></tr></thead>";
        echo "<tbody>";
        while ($store = $stmt->fetch()) {
            echo "<tr><td >".$store->getStoreName()."</td><td >".$store->getStoreTotalOrders($product_id)."</td><td>".$store->getNbEnCours($product_id)."</td><td>".$store->getTotalStocks($product_id)."</td></tr>";
        }
        echo "</tbody>";                                                                                                                                                                                                                                                                                             
        echo "</table>";;
        echo"</div>";
    }
    else{
        echo"<h1 class='w3-center'>Vous n'avez pas la permission d'accès à ces données.</h1>";
    }
?>
<div class="w3-center w3-container"><br>


<a href="index.php"><button class="w3-button w3-light-green">Redirection page principale</button></a></div>
</body>
</html>