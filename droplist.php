<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet"  href = "style.css"/>
</head>
<body>
    



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


<div class = 'droplist'>
<form action="droplist.php">
  <label for="cylindre">Cylindré</label>
  <select name="cylindre" id="cylindre">
    <option value="">cylindré</option>
    <option value=""></option>
    <option value=""></option>
    <option value=""></option>
  </select>
</form>
</div>

<div class = 'droplist'>
<form action="droplist.php">
  <label for="modeles">Modèles</label>
  <select name="modeles" id="modeles">
    <option value="">modèles</option>
    <option value=""></option>
    <option value=""></option>
    <option value=""></option>
  </select>
</form>
</div>
</section>

</body>



<?php 
//base de donne en cour

try{
    $db = new PDO('mysql:host=localhost;dbname=test','root','');
    $db -> exec('SET NAMES "UTF8"');
}catch(PDOException $e){
    echo 'Erreur:'.$e ->getMessage();
    die();
}

$reponse = $db->query('SELECT model FROM PREFIX_ukooparts_engine');
 while ($donnees = $reponse->fetch())
 {
        echo '<select> <option>'.$donnees['model'].'</option> </select>' ;
 }

  $reponse = $db->query('SELECT name FROM PREFIX_ukooparts_manufacturer');
 while ($donnees = $reponse->fetch())
  {
       echo '<select> <option>'.$donnees['name'].'</option> </select>' ;
  }


 $reponse = $db->query('SELECT * FROM test');
  while ($donnees = $reponse->fetch())
  {
         echo '<select> <option>'.$donnees[''].'</option> </select>' ;
  }

?>




