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
   
    $result = $db->query("SELECT * FROM PREFIX_ukooparts_engine 
    WHERE id_ukooparts_engine_type = ".$_POST['id_ukooparts_engine_type']." AND active =1 ORDER BY model ASC");

if($result->rowCount() > 0){
    echo'<option value="">Select modéle</option>';
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        echo'<option value"'.$row['id_ukooparts_engine'].'">'.$row['model'].'</option>';
    }
}else{
    echo'<option value="">pas de type</option>';
}
}
?>