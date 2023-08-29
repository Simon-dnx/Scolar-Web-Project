<?php
class Product{
    private $product_id; // c'est les seules donnée que l'on récupère généralement d'un produit
    private $product_name;
    private $category_name;
    private $nbvente;
    private $lien;
    
    
    public function __construct($product_id,$product_name,$category_name,$nbvente,$lien){
        $this->product_id=$product_id;
        $this->product_name=$product_name;
        $this->category_name=$category_name;
        $this->nbvente=$nbvente;
        $this->lien="magasinsparproduit.php?product_id=".$this->product_id;
    }
    public function __toString(){
        return " Je suis un Produit de nom: ".$this->product_name.", de catégorie :".$this->category_name;
    }
    public function getProductName(){
        return $this->product_name;
    }
    public function getProductId(){
        return $this->product_id;
    }
    public function getProductCategory(){
        return $this->category_name;
    }
    public function getProductNbVente(){
        return $this->nbvente;
    }
    public function getProductLien(){
        $this->lien="magasinsparproduit.php?product_id=".$this->product_id;
        return $this->lien;
    }
}
class Customer{
    private $customer_id;
    private $first_name;
    private $last_name;
    private $admin;
    
    public function __construct($customer_id,$first_name,$last_name,$admin){
        $this->customer_id=$customer_id;
        $this->first_name=$first_name;
        $this->last_name=$last_name;
        $this->admin=$admin;
    }
    public function getCustomerId(){
        return $this->customer_id;
    }
    public function getFirstName(){
        return $this->first_name;
    }
    public function getLastName(){
        return $this->last_name;
    }
    public function getAdmin(){
        return $this->admin;
    }
    public function __toString(){
        return "Je suis un Customer d'id :".$this->customer_id.", mon prénom:".$this->first_name.", mon Nom:".$this->last_name;
    }
}

class Store {
    private $store_id;
    private $store_name;
    private $total_orders;

    public function __construct($store_id,$store_name,$total_orders) {
        $this->store_id = $store_id;
        $this->store_name = $store_name;
        $this->total_orders=$total_orders;
    }
    public function getStoreId(){
        return $this->store_id;
    }
    public function getStoreName(){
        return $this->store_name;
    }
    public function getStoreTotalOrders($product_id){
        try {
            $pdo = new PDO("pgsql:host=localhost;dbname=etd","uapv2102630","Ck1ehp");
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        $stmt = $pdo->prepare("select sum(quantity) as quantity from orders join order_items on orders.order_id = order_items.order_id
        where orders.store_id=".$this->store_id." and order_items.product_id=".$product_id);
        $stmt->execute();
        $store=$stmt->fetch(PDO::FETCH_ASSOC);
        $this->total_orders=$store['quantity'];
        return $this->total_orders;
    }
    public function __toString() {
        return "Je suis un Store, mon id est " . $this->store_id . ", mon nom est " . $this->store_name . ", mon nombre total de commande est :".$this->total_orders;
    }
    public function getNbEnCours($product_id){
        try {
            $pdo = new PDO("pgsql:host=localhost;dbname=etd","uapv2102630","Ck1ehp");
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        $stmt = $pdo->prepare("select sum(quantity) as quantity from orders join order_items on orders.order_id = order_items.order_id
        where orders.store_id=".$this->store_id." and orders.order_status=2 and order_items.product_id=".$product_id);
        $stmt->execute();
        $store=$stmt->fetch(PDO::FETCH_ASSOC);
        return $store['quantity'];
    }

    public function getTotalStocks($product_id){
        try {
            $pdo = new PDO("pgsql:host=localhost;dbname=etd","uapv2102630","Ck1ehp");
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        $stmt = $pdo->prepare("select sum(quantity) as quantity from stocks where store_id=".$this->store_id." and product_id=".$product_id);
        $stmt->execute();
        $store=$stmt->fetch(PDO::FETCH_ASSOC);
        return $store['quantity'];
    }
}


class Order {
    private $order_id;
    private $customer_id;
    private $order_status;
    private $order_date;
    private $required_date;
    private $shipped_date;
    private $store_id;
    private $staff_id;
    
    public function __construct($order_id, $customer_id, $order_status, $order_date, $required_date, $shipped_date, $store_id, $staff_id) {
        $this->order_id = $order_id;
        $this->customer_id = $customer_id;
        $this->order_status = $order_status;
        $this->order_date = $order_date;
        $this->required_date = $required_date;
        $this->shipped_date = $shipped_date;
        $this->store_id = $store_id;
        $this->staff_id = $staff_id;
    }
    public function __toString(){
        return "Je suis un Order d'id ".$this->order_id." le client qui m'a commandé est : ".$this->customer_id;
    }
    public function getStoreName(){
        try {
            $pdo = new PDO("pgsql:host=localhost;dbname=etd","uapv2102630","Ck1ehp");
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        $stmt=$pdo->prepare("SELECT store_name FROM stores WHERE store_id =".$this->store_id);
        $stmt->execute();
        $store=$stmt->fetch(PDO::FETCH_ASSOC);
        
        return $store['store_name'];
    }
    public function getOrderId() {
        return $this->order_id;
    }
    
    public function getCustomerId() {
        return $this->customer_id;
    }
    
    public function getOrderStatus() {
        return $this->order_status;
    }
    
    public function getOrderDate() {
        return $this->order_date;
    }
    
    public function getRequiredDate() {
        return $this->required_date;
    }
    
    public function getShippedDate() {
        return $this->shipped_date;
    }
    
    public function getStoreId() {
        return $this->store_id;
    }
    
    public function getStaffId() {
        return $this->staff_id;
    }
}

?>
