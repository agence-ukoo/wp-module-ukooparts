<?php     
if (isset($_COOKIE['Choix1']))
{
    setcookie('Choix1', $_GET['Choix1'], time() + 31536000, null, null, false, true);
}
if (isset($_COOKIE['Choix2']))
{
    setcookie('Choix2', $_GET['Choix2'], time() + 31536000, null, null, false, true);
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

<select id="type">
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

<select name="Choix1" id="marque">
<option value="">marque</option>
</select>
<select name="Choix2" id="modele">
<option value="">modele</option>
</select>
<select id="year">
<option value="">année</option>
</select>
<input name="Envoyer" type="submit" value="valider" />
  </form>

<?php
if (isset($_GET['Envoyer'])) {
 $_GET['Choix1']." ".$_GET['Choix2'];$_COOKIE['Choix1'] = $_GET['Choix1'];
 $_COOKIE['Choix2'] = $_GET['Choix2'];
 echo $_COOKIE['Choix1'],-$_COOKIE['Choix2'];
}else{
   
    echo 'pas de moto enregistrer';
}
?>

</section>




