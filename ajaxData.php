<?php
  try{
    $db = new PDO('mysql:host=localhost;dbname=ukooparts','root','');
    $db -> exec('SET NAMES "UTF8"');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo 'Erreur:'.$e ->getMessage();
    die();
}
if(!empty($_POST["id_ukooparts_engine_type"])){
   
    $result = $db->query("SELECT DISTINCT MANU.id_ukooparts_manufacturer AS id_manufacturer, MANU.name AS manufacturer FROM PREFIX_ukooparts_engine ENGIN inner join 
    PREFIX_ukooparts_manufacturer MANU ON ENGIN.id_ukooparts_manufacturer = MANU.id_ukooparts_manufacturer 
    WHERE id_ukooparts_engine_type = ".$_POST['id_ukooparts_engine_type']."");

if($result->rowCount() > 0){
    echo'<option value="">Select modéle</option>';
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        echo'<option value"'.$row['id_manufacturer'].'">'.$row['manufacturer'].'</option>';
    }
}else{
    echo'<option value="">pas de type</option>';
}
}
?>