<?php   

session_start();

if (isset($_COOKIE['Choix1']))
{
    if (isset($_COOKIE['Choix2']))
{
    if (isset($_COOKIE['Choix3']))
    {
        if (isset($_COOKIE['Choix4']))
        {
        setcookie('Choix1', $_GET['Choix1'],'Choix2', $_GET['Choix2'],'Choix3', $_GET['Choix3'],'Choix4', $_GET['Choix4'], time() + 31536000, null, null, false, true);
        }
    }
    }
}
?>

<!DOCTYPE html>
<style>
.dropall{
    width: 100%;
    height: 50px;
    text-align: center;
    display: flex;
    background-color: red;
}
</style>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" />
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $('#type').on('change', function(){
            var typeID = $(this).val();
            if(typeID){
                $.ajax({
                    type:'POST',
                    url:'wp-content/plugins/ajaxData.php',
                    data:'id_ukooparts_engine_type='+ typeID,
                    success:function(html){
                        $('#marque').html(html);
                    }
                });
            }else{
                $('#marque').html('<option value="">Select type first</option>');
                $('#modele').html('<option value="">Select type first</option>');
                $('#year').html('<option value="">Select type first</option>');

            }
        });
        $('#marque').on('change', function(){
            var marqueID = $(this).val();
            if(marqueID){
                $.ajax({
                    type:'POST',
                    url:'wp-content/plugins/ajaxData.php',
                    data:'id_ukooparts_manufacturer='+ marqueID,
                    success:function(html){
                        $('#modele').html(html);
                    }
                });
            }else{
                $('#modele').html('<option value="">Select type first</option>');
                $('#year').html('<option value="">Select type first</option>');
            }
        });
        $('#modele').on('change', function(){
            var modeleID = $(this).val();
            if(modeleID){
                $.ajax({
                    type:'POST',
                    url:'wp-content/plugins/ajaxData.php',
                    data:'id_ukooparts_engine='+ modeleID,
                    success:function(html){
                        $('#year').html(html);
                    }
                });
            }else{
                $('#year').html('<option value="">Select type first</option>');
            }
        });
    });
</script>
<?php
try  {
    $db = new PDO('mysql:host=localhost;dbname=ukooparts','root','');
    $db -> exec('SET NAMES "UTF8"');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo 'Erreur:'.$e ->getMessage();
    die();
}
?>
<form  action = "" method="GET"> 
    <div class="container">
        <?php
        $result = $db->query("SELECT * FROM PREFIX_ukooparts_engine_type_lang WHERE id_lang = 1 ORDER BY name ASC");
        ?>

        <select name = "Choix1" id="type">
            <option value="">select type</option>
            <?php 
            if($result->rowCount()> 0){
                while($row = $result->fetch(PDO::FETCH_ASSOC)){
                    echo'<option value="'.$row['id_ukooparts_engine_type'].'">'.$row['name'].'</option>';
                }
            }else{
                echo'<option value=""> pas de type</option>';
            }
            ?>
        </select>

        <select name="Choix2" id="marque">
            <option value="">marque</option>
        </select>
        <select name="Choix3" id="modele">
            <option value="">modele</option>
        </select>
        <select name = "Choix4" id="year">
            <option value="">année</option>
        </select>
        <input name="Envoyer" type="submit" value="valider" />
</form>
<?php
if (isset($_GET['Envoyer'])) {
    $_GET['Choix1']." ".$_GET['Choix2']."".$_GET['Choix3']."".$_GET['Choix4'];
    $_COOKIE['Choix1'] = $_GET['Choix1'];
    $_COOKIE['Choix2'] = $_GET['Choix2'];
    $_COOKIE['Choix3'] = $_GET['Choix3'];
    $_COOKIE['Choix4'] = $_GET['Choix4']; 

    if($_GET['Choix4'] > 0){
        $id_guest = round(rand(0, time())/500000);
        $requestSQL = $db -> prepare('INSERT INTO PREFIX_ukooparts_customer_engine (id_customer,id_guest,id_ukooparts_engine,owned,current,date_upd,date_add)
        VALUES (:id_customer,:id_guest,:id_ukooparts_engine,:owned,:current,now(),now())');
        $requestSQL-> bindValue(':id_customer',1);
        $requestSQL-> bindValue(':id_guest', $id_guest);
        $requestSQL-> bindValue(':id_ukooparts_engine',$_COOKIE['Choix3']);
        $requestSQL-> bindValue(':owned',1); 
        $requestSQL-> bindValue(':current',1);
        $requestSQL->closeCursor();
        $requestSQL->execute();

        $vehicule = $db -> query("SELECT cengine.id_ukooparts_engine, engine.id_ukooparts_manufacturer, engine.model, manu.name
            FROM PREFIX_ukooparts_customer_engine cengine
            INNER JOIN PREFIX_ukooparts_engine engine
            ON cengine.id_ukooparts_engine = engine.id_ukooparts_engine
            INNER JOIN PREFIX_ukooparts_manufacturer manu
            ON manu.id_ukooparts_manufacturer = engine.id_ukooparts_manufacturer
            ORDER BY cengine.date_upd DESC LIMIT 1;")->fetchAll();
        // set session variable to filter page manufacturer and page models
        $_SESSION['id_manufacturer'] = $_GET['Choix2'];
        $_SESSION['id_engine'] = $_GET['Choix3'];

        echo "Moto séléctionné : ".$vehicule[0]["name"]." ".$vehicule[0]["model"];
    } else {
        
        $_SESSION['id_manufacturer'] = null;
        $_SESSION['id_engine'] = null;
        echo "";
    }
}else{
    if(isset($_SESSION['id_manufacturer'])){
        $vehicule = $db -> query("SELECT cengine.id_ukooparts_engine, engine.id_ukooparts_manufacturer, engine.model, manu.name
            FROM PREFIX_ukooparts_customer_engine cengine
            INNER JOIN PREFIX_ukooparts_engine engine
            ON cengine.id_ukooparts_engine = engine.id_ukooparts_engine
            INNER JOIN PREFIX_ukooparts_manufacturer manu
            ON manu.id_ukooparts_manufacturer = engine.id_ukooparts_manufacturer
            ORDER BY cengine.date_upd DESC LIMIT 1;")->fetchAll();
        
        echo "Moto séléctionné : ".$vehicule[0]["name"]." ".$vehicule[0]["model"];
    }else {
        echo "";
    }
}
?>
</section>
