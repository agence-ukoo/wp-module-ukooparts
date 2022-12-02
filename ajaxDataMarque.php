<?php
  try{
    $db = new PDO('mysql:host=localhost;dbname=ukooparts','root','');
    $db -> exec('SET NAMES "UTF8"');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo 'Erreur:'.$e ->getMessage();
    die();
}
if(!empty($_POST["id_manufacturer"])){
   
    $result = $db->query("SELECT DISTINCT MANU.id_ukooparts_manufacturer AS id_manufacturer,ENGIN.id_ukooparts_engine, ENGIN.model as modele FROM PREFIX_ukooparts_engine ENGIN inner join 
    PREFIX_ukooparts_manufacturer MANU ON ENGIN.id_ukooparts_manufacturer = MANU.id_ukooparts_manufacturer 
    WHERE id_ukooparts_engine_type = ".$_POST['id_manufacturer']."");

if($result->rowCount() > 0){
    echo'<option value="">Select modéle</option>';
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        echo'<option value"'.$row['id_ukooparts_engine'].'">'.$row['modele'].'</option>';
    }
}else{
    echo'<option value="">pas de type</option>';
}
}
?>