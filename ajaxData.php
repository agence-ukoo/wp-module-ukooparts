
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
   
    $result = $db->query("SELECT DISTINCT MANU.id_ukooparts_manufacturer, MANU.name AS manufacturer FROM PREFIX_ukooparts_engine ENGIN inner join 
    PREFIX_ukooparts_manufacturer MANU ON ENGIN.id_ukooparts_manufacturer = MANU.id_ukooparts_manufacturer 
    WHERE id_ukooparts_engine_type = '".$_POST['id_ukooparts_engine_type']."'");

if($result->rowCount() > 0){
    echo'<option value="">Select marque</option>';
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        echo'<option value="'.$row['id_ukooparts_manufacturer'].'">'.$row['manufacturer'].'</option>';
    }
}else{
    echo'<option value="">pas de marque</option>';
}
}elseif(!empty($_POST["id_ukooparts_manufacturer"])){

    $result = $db->query("SELECT DISTINCT MANU.id_ukooparts_manufacturer,MANU.name,ENGIN.id_ukooparts_engine, ENGIN.model as modele 
    FROM PREFIX_ukooparts_engine ENGIN 
    inner join PREFIX_ukooparts_manufacturer MANU ON ENGIN.id_ukooparts_manufacturer = MANU.id_ukooparts_manufacturer
    WHERE MANU.id_ukooparts_manufacturer = '".$_POST['id_ukooparts_manufacturer']."'");

if($result->rowCount() > 0){
    echo'<option value="">Select modele</option>';
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        echo'<option value="'.$row['id_ukooparts_engine'].'">'.$row['modele'].'</option>';
    }
}else{
    echo'<option value="">pas de modele</option>';
}
}elseif(!empty($_POST["id_ukooparts_engine"])){

    $result = $db->query("SELECT DISTINCT MANU.id_ukooparts_manufacturer,MANU.name,ENGIN.id_ukooparts_engine,ENGIN.year_start,ENGIN.year_end, ENGIN.model as modele 
    FROM PREFIX_ukooparts_engine ENGIN 
    inner join PREFIX_ukooparts_manufacturer MANU ON ENGIN.id_ukooparts_manufacturer = MANU.id_ukooparts_manufacturer
    WHERE ENGIN.id_ukooparts_engine = '".$_POST['id_ukooparts_engine']."'");
  

    var_dump($end);
if($result->rowCount() > 0){
    echo'<option value="">Select année</option>';
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        for($date= $row['year_start']; $date <= $row['year_end']; $date++) {
        echo'<option value"'.$row['year_start'].'">'.$date.'</option>'; }   }
}else{
    echo'<option value="">aucune année</option>';
}
}
?>
