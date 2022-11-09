<?php

/*
Plugin Name: Ukooparts
Plugin URI: https://www.ukoo.fr/
Description: Ukooparts for WooCommerce !
Version 0.1
Author: Ukoo
Author URI: https://www.ukoo.fr/
 */

if (!defined('ABSPATH')) exit();

define('UKOOPARTS_PLUGIN_DIR',plugin_dir_path(__FILE__));

require UKOOPARTS_PLUGIN_DIR . 'vendor/autoload.php';

$plugin = new Ukoo\Ukooparts\UkooPartsPlugin(__FILE__);

add_action('wp_head', 'test');

function test(): void{

}



// function to create pages
function create_page($title, $content, $status){
    $page_array = array(
        'post_title' => $title,
        'post_content' => $content,
        'post_status' => $status,
        'post_type' => 'page'
    );
    $new_page = get_page_by_title( $title, OBJECT, 'page');
    if (  !isset( $new_page ) ) {
        wp_insert_post($page_array, false);
	}
}
// call the function to create a page title "moto"
create_page('moto', 'all the motos are here', 'publish');