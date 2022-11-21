<?php
/**
 * @package Liste des constructeurs (A-Z)
 * @version 1.0.0
 */
/*
Plugin Name: Liste des constructeurs (A-Z)
Plugin URI: https://fr.wikipedia.org/wiki/Gimbap
Description: Affichage des constructeurs triées par ordre alphabétique. (A-Z)
Version: 1.0.0
Author: Vincent Vogel
*/




add_action('wp_head', 'displayManufacturers');

function displayManufacturers() {
    $manufacturers_list = json_decode(file_get_contents('http://' . $_SERVER["HTTP_HOST"] . '/ukooparts/wp-content/plugins/manufacturers/arrayTest/manufacturersArray.php'), true);

    // print_r($manufacturers_list);
    foreach($manufacturers_list as $manufacturer) : ?>


      <img src="<?php echo $manufacturer['logo']; ?>" width="100px" />
      <h6> <?php echo ($manufacturer['brand']); ?></h6>
    
        <?php endforeach ?>
<?php } ?>





