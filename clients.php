<?php
// Lien du site : https://pedago.univ-avignon.fr/~uapv2102630/MiniprojetDBWEB4/index.php

try {
    $pdo=new PDO("pgsql:host=localhost;dbname=etd","uapv2102630","Ck1ehp");
} catch (PDOException $e) {
    echo $e->getMessage();
}

$req=$pdo->prepare("SELECT * from customers order by customer_id");
$req->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,"Customer", array(null,null,null,null));
$req->execute();

//header('Location: ' . $_SERVER['PHP_SELF']);
?>

<br>
<?php
    if(isset($_SESSION['admin'])){
        if($_SESSION['admin']==true){
            ?>
            <div class="W3-center">
            <button id="modal" onclick="document.getElementById('modalAdmin').style.display='block'" class="w3-center W3-black w3-button">Administration</button>
            </div>
            <?php
        }
    }
    
    if(isset($_POST['custo_id'])){
        try {
            $pdo2=new PDO("pgsql:host=localhost;dbname=etd","uapv2102630","Ck1ehp");
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $customer_id=$_POST['custo_id'];
        $first_name=$_POST['first_name'];
        $last_name=$_POST['last_name'];
        $phone=$_POST['phone'];
        $email=$_POST['email'];
        $street=$_POST['street'];
        $city=$_POST['city'];
        $state=$_POST['state'];
        $zipcode=$_POST['zip_code'];
        $admin=$_POST['admin'];
        $update=$pdo2->prepare("update customers set first_name = '".$first_name."', last_name = '".$last_name."',
            phone = '".$phone."',email = '".$email."',street = '".$street."',city = '".$city."',state ='".$state."',zip_code ='".$zipcode."',admin=".$admin." where customer_id=".$customer_id);
            $update->execute();
            header('Location: ' . $_SERVER['PHP_SELF']);
    }
    else{
        if(isset($_POST['first_name'])&&isset($_POST['last_name'])&&isset($_POST['phone'])&&isset($_POST['email'])&&isset($_POST['street'])&&isset($_POST['city'])&&isset($_POST['state'])&&isset($_POST['zip_code'])&&isset($_POST['admin'])){
        try {
            $pdo2=new PDO("pgsql:host=localhost;dbname=etd","uapv2102630","Ck1ehp");
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $first_name=$_POST['first_name'];
        $last_name=$_POST['last_name'];
        $phone=$_POST['phone'];
        $email=$_POST['email'];
        $street=$_POST['street'];
        $city=$_POST['city'];
        $state=$_POST['state'];
        $zipcode=$_POST['zip_code'];
        $admin=$_POST['admin'];
        $update=$pdo2->prepare("INSERT INTO customers(first_name, last_name, phone, email, street, city, state, zip_code,admin) VALUES('".$first_name."','".$last_name."','".$phone."','".$email."','".$street."','".$city."','".$state."','".$zipcode."',".$admin.")");
        $update->execute();
        header('Location: ' . $_SERVER['PHP_SELF']);
    }}
?>

<div id="modalAdmin" class="w3-modal">

            <div class="w3-modal-content">
            <span onclick="document.getElementById('modalAdmin').style.display='none'" class="w3-button w3-display-topright">&times;</span>
                <div class="w3-container">
                    <h2 class="w3-center w3-light-green">Ajout/Modification de client</h2>
                    <p>Grâce a ce formulaire vous pouvez ajouter ou bien modifier un client. Si vous entrez un id existant vous pourrez modifer le client avec cet id
                        . Si l'id n'existe pas alors un nouveau client sera crée.
                    </p>
                </div>
                <div class="w3-container">
                <form method="POST">
                        <label for="customer_id">ID :</label>
                        <input class="w3-input" type="text" name="customer_id" id="customer_id" placeholder=<?php if(isset($_POST['customer_id'])) echo $_POST['customer_id']; ?>>
                        <br>
                        <p>Appuyez sur le bouton si dessous pour vérifier si le client existe : (Vous devriez rappuyer sur le bouton administration pour avoir tout le formulaire une fois ceci fait.)</p>
                        <button class="w3-button" type="submit" >Récuperer les informations</button>
                </form>
</div>



            </div>
        </div>
<?php 
    try {
        $pdo2=new PDO("pgsql:host=localhost;dbname=etd","uapv2102630","Ck1ehp");
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    if(isset($_POST['customer_id'])){
    $requete=$pdo2->prepare("select * from customers where customer_id=".$_POST['customer_id']);
    $requete->execute();
    $Userexistant=$requete->fetch();
?>
<div class="w3-container">
    <?php 
        if($Userexistant!=NULL){
            if($Userexistant['admin']==true){
                echo "VOUS NE POUVEZ PAS MODIFIER LES INFORMATIONS D'UN ADMINISTRATEUR";
            }
            else{
    ?>
    <form method="POST" onsubmit="VerifInput(event)">
        <label for="customer_id">ID :<?php echo $Userexistant['customer_id'] ?></label>
        <input class="w3-input" name="custo_id" value="<?php echo $Userexistant['customer_id'] ?>" hidden>
        <label for="first_name">Prénom :</label>
        <input class="w3-input" type="text" name="first_name" id="first_name" placeholder="<?php echo $Userexistant['first_name'] ;?>">
        <br>
        <label for="last_name">Nom :</label>
        <input class="w3-input" type="text" name="last_name" id="last_name" placeholder="<?php  echo $Userexistant['last_name'] ;?>">
        <br>
        <label for="phone">Téléphone :</label>
        <input class="w3-input" type="text" name="phone" id="phone" placeholder="<?php echo $Userexistant['phone'] ;?>">
        <br>
        <label for="email">Email :</label>
        <input class="w3-input" type="text" name="email" id="email" placeholder="<?php echo $Userexistant['email'] ;?>">
        <br>
        <label for="street">Rue :</label>
        <input class="w3-input" type="text" name="street" id="street" placeholder="<?php echo $Userexistant['street'] ;?>">
        <br>
        <label for="city">Ville :</label>
        <input class="w3-input" type="text" name="city" id="city" placeholder="<?php echo $Userexistant['city'] ; ?>">
        <br>
        <label for="state">Etat :</label>
        <input class="w3-input" type="text" name="state" id="state" placeholder="<?php echo $Userexistant['state'] ; ?>">
        <br>
        <label for="zip_code">Code Postal :</label>
        <input class="w3-input" type="text" name="zip_code" id="zip_code" placeholder="<?php echo $Userexistant['zip_code'] ;?>">
        <label for="admin">Droits d'Administration :</label>
        <input class="w3-radio" type="radio" name="admin" value="true" id="admin_true">
        <label for="admin_true">Vrai</label>
        <input class="w3-radio" type="radio" name="admin" value="false" id="admin_false">
        <label for="admin_false">Faux</label><br>
        <button class="w3-button" type="submit" >Soumettre</button>
    </form>

    <?php }}else{ ?>
        <p>Le client n'existe pas. On va en créer un nouveau.</p>
        <form method="POST" onsubmit="VerifInput(event)">
        <label for="customer_id">ID :</label>
        <label for="first_name">Prénom :</label>
        <input class="w3-input" type="text" name="first_name" id="first_name" placeholder="Prénom">
        <br>
        <label for="last_name">Nom :</label>
        <input class="w3-input" type="text" name="last_name" id="last_name" placeholder="Nom">
        <br>
        <label for="phone">Téléphone :</label>
        <input class="w3-input" type="text" name="phone" id="phone" placeholder="Téléphone">
        <br>
        <label for="email">Email :</label>
        <input class="w3-input" type="text" name="email" id="email" placeholder="Email">
        <br>
        <label for="street">Rue :</label>
        <input class="w3-input" type="text" name="street" id="street" placeholder="Rue">
        <br>
        <label for="city">Ville :</label>
        <input class="w3-input" type="text" name="city" id="city" placeholder="Ville">
        <br>
        <label for="state">Etat :</label>
        <input class="w3-input" type="text" name="state" id="state" placeholder="Etat">
        <br>
        <label for="zip_code">Code Postal :</label>
        <input class="w3-input" type="text" name="zip_code" id="zip_code" placeholder="Code Postal">
        <label for="admin">Droits d'Administration :</label>
        <input class="w3-radio" type="radio" name="admin" value="true" id="admin_true">
        <label for="admin_true">Vrai</label>
        <input class="w3-radio" type="radio" name="admin" value="false" id="admin_false">
        <label for="admin_false">Faux</label><br>
        <button class="w3-button" type="submit" >Soumettre</button>
    </form>
</div>
<?php }}?>
<?php
echo "<div class='w3-container'><table class='w3-table w3-striped'>";
echo "<tr><th>ID</th><th>Prénom</th><th>Nom</th></tr>";

while($client = $req->fetch()){
    ?>
    <tr <?php
        if($client->getAdmin()==true){
            echo "class='admin'";
        }
    ?>>
    <td><?php echo $client->getCustomerId()?></td>
    <td><a href="index.php?client=<?php echo $client->getCustomerId(); ?>"><?php echo $client->getFirstName(); ?></td>
    <td><?php echo $client->getLastName(); ?></td></a>
    </tr>
   <?php
}
echo"</div>";
?>
<script>
function VerifInput(event) {
    // Récupération des champs de texte
    const firstNameInput = document.getElementById('first_name');
    const lastNameInput = document.getElementById('last_name');
    const phoneInput = document.getElementById('phone');
    const emailInput = document.getElementById('email');
    const streetInput = document.getElementById('street');
    const cityInput = document.getElementById('city');
    const stateInput = document.getElementById('state');
    const zipCodeInput = document.getElementById('zip_code');
    const confInput=document.getElementById('conf');
    // Vérification que les champs de texte ne sont pas vides
    if (
        firstNameInput.value.trim() === '' ||
        lastNameInput.value.trim() === '' ||
        phoneInput.value.trim() === '' ||
        emailInput.value.trim() === '' ||
        streetInput.value.trim() === '' ||
        cityInput.value.trim() === '' ||
        stateInput.value.trim() === '' ||
        zipCodeInput.value.trim() === ''||
        confInput.value.trim()===''
    ) {
        event.preventDefault(); // Empêche la soumission du formulaire
        alert('Veuillez remplir tous les champs de texte.');
    }
}
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  // Sélectionner toutes les lignes avec la classe "admin"
  $("tr.admin").hover(function() {
    // Lorsque la souris entre dans la ligne
    $(this).find("td:eq(2)").css("transform", "scale(2)");
  }, function() {
    // Lorsque la souris quitte la ligne
    $(this).find("td:eq(2)").css("transform", "scale(1)");
  });
});

</script>
</style>

