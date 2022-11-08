<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href=".css" />
</head>
<body>
    
<?php
try{
    $db = new PDO('mysql:host=localhost;dbname=ukooparts','root','');
    $db -> exec('SET NAMES "UTF8"');
}catch(PDOException $e){
    echo 'Erreur:'.$e ->getMessage();
    die();
}
?>

<div>
<form action="droplist.php">
  <label for="Marque">Marque</label>
  <select name="Marque" id="Marque">
    <option value="volvo"></option>

  </select>
</form>
</div>


<div>
<form action="droplist.php">
  <label for="cylindré">cylindré</label>
  <select name="cylindré" id="cylindré">
    <option value=""></option>
    <option value=""></option>
    <option value=""></option>
    <option value=""></option>
  </select>
</form>
</div>


<div>
<form action="droplist.php">
  <label for="modèles">modèles</label>
  <select name="modèles" id="modèles">
    <option value=""></option>
    <option value=""></option>
    <option value=""></option>
    <option value=""></option>
  </select>
</form>
</div>


</body>
