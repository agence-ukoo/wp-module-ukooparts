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

add_action('wp_footer','marque');
function marque(){
    printf(' <div class="titrediv" id="titrecss">
            <h2 id="titre"><span> Nos constructeurs moto route </span></h2>
            </div>
            <div class="triangleRouge" id="triangle">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/Red_triangle.svg/540px-Red_triangle.svg.png" />
            </div>
            <div class="container" id="containerLogo">
            <div class="logoYamaha" id="yamaha">
            <a href="https://www.tech2roo.com/compat/yamaha/850/mt-09/158-159-403"><img src="https://i.pinimg.com/originals/0b/c0/24/0bc024f240e6bec6d29df3155d487adf.png" /></a>
            </div>
            <div class="logoKawasaki" id="kawasaki">
            <a href="https://www.tech2roo.com/compat/kawasaki/650/er-6f-sans-abs/161-140-181"><img src="https://www.freepnglogos.com/uploads/kawasaki-png-logo/kawasaki-green-emblem-png-logo-1.png" /></a>
            </div>           
            <div class="logoSuzuki" id="suzuki">
            <a href="https://www.tech2roo.com/compat/suzuki/750/gsr750/153-17-343"><img src="https://seeklogo.com/images/S/suzuki-logo-B2B31D667D-seeklogo.com.png" /></a>
            </div>           
            <div class="logoAprilia" id="aprilia">
            <a href="https://www.tech2roo.com/compat/aprilia/150/scarabeo/2005/418-150-910-12"><img src="https://www.autocollant-tuning.com/2143-home_default/autocollant-aprilia-sport.jpg" /></a>
            </div>                       
            <div class="logoBmw" id="bmw">
            <a href="https://www.tech2roo.com/compat/bmw/1000/r-100-r/1997/1-24-753-77"><img src="https://assets.stickpng.com/thumbs/580b57fcd9996e24bc43c46e.png" /></a>
            </div>       
            </div>                
            <p style="text-align: center;"> voir tout les <a href="https://www.tech2roo.com/">constructeurs </a></p>'
        );
}

function typesCss(){
    echo "
    <style type='text/css'>
    #titre {
        position: relative;
        overflow: hidden;
        text-align: center;
        width: 86%;
        font-weight: bold;
        font-size: 130%;
        text-transform: uppercase;
    }
    #titrecss{
        display: flex;
        width: 100%;
        justify-content:center;
    }

    #titre:before,  #titre:after {
        position: absolute;
        top: 51%;
        overflow: hidden;
        width: 50%;
        height: 1px;
        margin-left: 1%;
        content: '\a0';
        background-color: 
    #000;
    }
    #titre:before {
        margin-left: -51%;
        text-align: right;
    }

    #yamaha{
        display: flex;
    }
    #containerLogo{
        margin-top:5%;
        display: flex;
        flex-direction: row;
    }
    #triangle{
        width: 1%;
        height: 1%;
        margin-top: -1%;
        margin-left: 50%;
        display: flex;
        flex-direction: row;
        position: center;
        align-self: center;
    }
    </style>
    ";
}

    add_action( 'wp_footer','typesCss');


