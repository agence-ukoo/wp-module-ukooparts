<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet"  href = "style.css"/>
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




<section class="dropall">
<div class = 'droplist'>
<?php $marques ?>
<form action="droplist.php">
  <label for="Marque">Marque</label>
  <select name="Marque" id="Marque">
    <option value="">Marque</option>
    <option value="">Aprilia</option>
    <option value="">BMW</option>
    <option value="">Honda</option>
    <option value="">KTM</option>
    <option value="">Kawazaki</option>
    <option value="">Moto-Guizzi</option>
  </select>
</form>
</div>

<?php $cylindre ?>

<div class = 'droplist'>
<form action="droplist.php">
  <label for="cylindré">Cylindré</label>
  <select name="cylindré" id="cylindré">
    <option value="">cylindré</option>
    <option value=""></option>
    <option value=""></option>
    <option value=""></option>
  </select>
</form>
</div>


<?php 
 $sql ='SELECT model FROM PREFIX_ukooparts_engine';
 $ah = $db->query($sql)->fetchAll();
echo $ah['model']
?>
<div class = 'droplist'>
<form action="droplist.php">
  <label for="modèles">Modèles</label>
  <select name="modèles" id="modèles">
    <option value="">modèles</option>
    <option value=""></option>
    <option value=""></option>
    <option value=""></option>
  </select>
</form>
</div>
</section>

</body>

