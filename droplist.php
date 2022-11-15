<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" />
</head>
<body>
 <?php    
try{
    $db = new PDO('mysql:host=localhost;dbname=test','root','');
    $db -> exec('SET NAMES "UTF8"');
}catch(PDOException $e){
    echo 'Erreur:'.$e ->getMessage();
    die();
}
?>

<section class="dropall">
<div class = 'droplist'>
<?php $marques ?>
<form action="droplist.php">
  <label for="Marque">Marque</label>
  <select>
<?php
    foreach ($db->query('SELECT name FROM PREFIX_ukooparts_manufacturer') as $row) {
        echo '<option value="' . $row['name'] . '">'. $row['name'] . ' </option>';
    }   
?>
</select>
</form>
</div>


<div class = 'droplist'>
<form action="droplist.php">
  <label for="cylindre">Cylindré</label>
  <select>
<?php
    foreach ($db->query('SELECT displacement FROM PREFIX_ukooparts_engine') as $row) {
        echo '<option value="' . $row['displacement'] . '">'. $row['displacement'] . ' </option>';
    }   
?>
</select>
</form>
</div>

<div class = 'droplist'>
<form action="droplist.php">
  <label for="model">Modèles</label>
  <select>
<?php
    foreach ($db->query('SELECT model FROM PREFIX_ukooparts_engine') as $row) {
        echo '<option value="' . $row['model'] . '">'. $row['model'] . ' </option>';
    }   
?>
</select>

<div class = 'droplist'>
<form action="droplist.php">
  <label for="year">année</label>
  <select>
<?php
    foreach ($db->query('SELECT  FROM PREFIX_ukooparts_compatibility') as $row) {
        echo '<option value="' . $row['year'] . '">'. $row['year'] . ' </option>';
    }   
?>
</select>

</form>
</div>
</section>

</body>




