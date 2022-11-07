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




add_action('wp_footer', 'display_manufacturers');

function display_manufacturers() {
    echo('<p>Liste des marques : Yamaha, Kawasaki, Triumph...</p>');
}


?>




<?php

// exemples

// add_action('wp_footer', 'display_manufacturers');
// add_filter('default_content', 'contenu_par_defaut');
// add_filter('the_content', 'insertAfterContent');
// add_shortcode('newShortcode', 'manageShortcode');

// function display_manufacturers() {
//     echo('<p>Liste des marques : Yamaha, Kawasaki, Triumph...</p>');
// }

// function manageShortcode() {
//     echo('<p>_____THIS IS A SHORTCODE_____</p>');
// }

// function contenu_par_defaut() {
//     return "Template par défaut";
// }

// function insertAfterContent($content) {
//     $content .= '<p>ceci est du contenu</p>';
//     return $content;
// }

?>