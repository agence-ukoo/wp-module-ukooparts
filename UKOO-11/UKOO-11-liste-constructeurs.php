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
        // test de la function avec un simple paragraphe 
    //echo('<p>Liste des marques : Yamaha, Kawasaki, Triumph...</p><br />');

        // test utilisation d'un loop pour display les infos d'un array
    $constructeurs = json_decode(file_get_contents('http://localhost/ukooparts/wp-content/plugins/UKOO-11/array_constructeurs.php'));
    //print_r($constructeurs);

    foreach($constructeurs as $constructeur) {
        echo ("<h4> $constructeur </h4>");
    }
}
?>

<?php

    // ajout d'un peu de CSS pour tester.
function manufacturer_css() {
 echo "
 <style type='text/css'>
    h4 {
    color: red;
    display: flex;
    flex-direction: row;
    }
 </style>
 ";
}
 
add_action( 'wp_head', 'manufacturer_css' );

?>

