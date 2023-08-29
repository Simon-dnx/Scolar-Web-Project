

<?php 
    session_start();
    include "objet.php";// page qui contient la modelisation objet
    if (isset($_POST['destroy_session'])) {
        session_destroy();
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    if(isset($_POST['login'])&&isset($_POST['mdp'])){
        $_SESSION['login']=$_POST['login'];
        $_SESSION['mdp']=$_POST['mdp'];
    }
?>


<html>  <!-- Le lien de la page du projet est : https://pedago.univ-avignon.fr/~uapv2102630/MiniprojetDBWEB4/index.php -->
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>My bike</title>
    </head>
    <header class="w3-container w3-light-green"> 
        <?php
        if(!isset($_SESSION['login'])||!isset($_SESSION['mdp'])){// Si le client n'est pas connecté j'affiche un bouton login
            ?>
            <h2 class="w3-center">Connexion</h2>
            <div class="W3-center">
                <button id="modal" onclick="document.getElementById('id01').style.display='block'" class=" W3-black w3-button">Login</button>
            </div>
            
        <?php
        }
        else{
            try{
                $pdo=new PDO("pgsql:host=localhost;dbname=etd","uapv2102630","Ck1ehp");
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            $req2=$pdo->prepare("SELECT * from customers where customer_id=".$_SESSION['mdp']);
            $req2->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,"Customer", array(null,null,null,null));
            $req2->execute();
            $client=$req2->fetch();

            if($client!=false&&!$client->getAdmin()){ // Je vérifie si c'est un admin
                if($_SESSION['login']==$client->getLastName()&&$_SESSION['mdp']==$client->getCustomerId()){// je vérifie si c'est un client avec les bons logins
                    $_SESSION['admin']=false;
                    echo "<h2 class='w3-center'>Bonjour MR/MME ".$client->getLastName()." !</h2>";
                    echo"<h3 class='w3-center'>Vous pouvez acceder à l'état des commandes grâce au boutton ci dessous.</h3>";
                    echo"<div class='w3-container w3-center'>";
                    echo"<form class='w3-center' method='get'><button type='submit' name='page' value='etatdescommandes'class='w3-lime w3-button'>Etat des commandes</button></form>";
                    echo "<form method='post' action='index.php'>
                            <button type='submit' name='destroy_session' value='destroy' class='w3-black w3-button'>Déconnexion</button>
                            </form></div>";
                }
                else{
                    echo "<h2 class='w3-center'>Identifient incorrect ! </h2>";
                    echo "<form method='post'>
                            <button type='submit' name='destroy_session' value='destroy' class='w3-black w3-button'>Redirection</button>
                            </form></div>";;
                }
            }
            else{
                if($client!=false&&$_SESSION['login']==$client->getLastName()&&$_SESSION['mdp']==$client->getCustomerId()){
                    echo "<h2 class='w3-center'>Bonjour administrateur ".$client->getLastName()." !</h2>";
                    echo "<div='w3-container w3-center'><form class='w3-center' method='get'><button type='submit' name='page' value='etatdescommandes' class='w3-lime w3-button'>Etat des commandes</button></form>";
                    echo "<form class='w3-center' method='post'>
                            <button type='submit' name='destroy_session' value='destroy' class='w3-black w3-button'>Déconnexion</button>
                            </form></div>";
                    $_SESSION['admin']=true;
                }
                else{
                    echo "<h2 class='w3-center'>Identifient incorrect ! </h2>";
                    echo"<div class='w3-container w3-center'>";
                    echo "<form method='post' >
                            <button type='submit' name='destroy_session' value='destroy' class='w3-black w3-button'>Redirection</button>
                            </form></div>";
                }
            }
            if(isset($_GET['page'])){
                if($_GET['page']=="etatdescommandes"){
                    echo"<a href='index.php'><button class='w3-lime w3-button'>Retourner à la liste des clients</button></a>";
                }
            }
            
        }
        ?>
        </header>

        <div id="id01" class="w3-modal">
            
            <div class="w3-modal-content">
                <div class="w3-container">
                    <h2 class="w3-center">Connexion</h2>
                    <p>Attention ce formulaire de connection est valable seulement pour les administrateur, ou les clients.</p>
                </div>
                <div class="w3-container">
                    <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-display-topright">&times;</span>
                    <form method="POST">
                        <label>Votre login :</label>
                        <input class="w3-input" type="text" name="login">
                        <label>Votre mot de passe :</label>
                        <input class="w3-input" type="text" name="mdp">
                        <button class="w3-button w3-lime" type="submit">Soumettre</button>
                    </form>
                </div>
            </div>
        </div>
    <body>
    
<?php
if(isset($_GET['client'])){
    include "commandes.php";
}
if(!isset($_GET['page'])){ 
    

    include 'clients.php'; // Il faut cliquer sur le nom du clients pour afficher ses commandes 
    
/* Les requetes que j'ai effectuée pour modifier la base de donné pour ajouter l'attribut admin a la table customers est :
    alter table customers add admin BOOLEAN DEFAULT false;
    Grace a cette commande les anciens customers de la base de donnée originelle ne sont pas admin

    j'ai rajouté un customers Simon Diaz qui sera admin (id:1446)
    insert into customers(first_name, last_name, phone, email, street, city, state, zip_code, admin) values('Simon','Diaz',null,'simon@gmail.com','153 chemin 
de Spinoza','Tarascon','PACA','13150',true);

*/

}   
else{
    if($_GET['page']=="etatdescommandes"){
        include "etatdescommandes.php";
    }
}

?>
</body>
</html>
