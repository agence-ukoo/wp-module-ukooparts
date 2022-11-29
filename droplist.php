<?php
// setcookie(time()+3600*24*365);
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

<select id="marque">
<option value="">marque</option>
</select>
<select id="modele">
<option value="">modele</option>
</select>
<select id="year">
<option value="">année</option>
</select>
</div>
