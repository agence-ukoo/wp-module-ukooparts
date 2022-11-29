<?php     

if (isset($_COOKIE['Choix1']))
{
    setcookie('Choix1', $_GET['Choix1'], time() + 31536000, null, null, false, true);
}


?>

<!DOCTYPE html>
<style>
.dropall{
    width: 100%;
    height: 50%;
    text-align: center;
    display: flex;}
</style>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" />
</head>

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
<form  action = "" method="GET">
  <label for="Marque">Marque</label>
  <select name="Choix1" >
<?php
    foreach ($db->query('SELECT name FROM PREFIX_ukooparts_manufacturer') as $row) {
        echo '<option value="' . $row['name'] . '">'. $row['name'] . ' </option>';
    }
?>
</select>

  <label for="model">Modèles</label>
  <select name="Choix2">
<?php foreach ($db->query('SELECT model FROM PREFIX_ukooparts_engine ') as $row) {
        echo '<option value="' . $row['model'] . '">'. $row['model'] . ' </option>';}?>
</select>

  <label for="cylindre">Cylindré</label>
  <select name="Choix3">
<?php
    foreach ($db->query('SELECT displacement FROM PREFIX_ukooparts_engine') as $row) {
        echo '<option value="' . $row['displacement'] . '">'. $row['displacement'] . ' </option>';}?>
</select>
<input name="Envoyer" type="submit" value="valider" />
  </form>

<?php
if (isset($_GET['Envoyer'])) {
 echo "Vous avez selectioner ".$_COOKIE[$_GET['Choix1']]," ".$_COOKIE[$_GET['Choix2']];}
?>

</section>




