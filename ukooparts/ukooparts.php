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



// function to create pages  yuhan/////////////////////////////////////////////////////////////////////:
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




//////////////Vincent Pages ********************************
function create_manufacturers_page($title, $content, $status){
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
// call the function to create a page title "marque"
create_manufacturers_page('manufacturers', 'all the manufacturers are here', 'publish');

/////////////////////////////Adam/////////////////////////////////////////////////

/*add_action('wp_header', 'dropdown_view');
function create_dropList($title, $content, $status){
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

create_dropList('droplist test', '<section class="dropall">
<div class = "droplist">
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

<div class = "droplist">
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

<div class = "droplist">
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
</section>',
'publish'
);*/








////////////////////////////////////ilies////////////////////////////////////////////
add_action('wp_footer','marque');
add_action( 'wp_footer','typesCss1');

function marque(){
    printf(' <div class="titrediv" id="titrecss">
            <h2 id="titre"><span> Nos constructeurs moto route </span></h2>
            </div>
            <div class="triangleRouge" id="triangle">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/Red_triangle.svg/540px-Red_triangle.svg.png" />
            </div>
            <div class="container" id="containerLogo">
            <div class="logo" id="yamaha">
            <a href="https://www.tech2roo.com/compat/yamaha/850/mt-09/158-159-403"><img class="logo" src="https://i.pinimg.com/originals/0b/c0/24/0bc024f240e6bec6d29df3155d487adf.png" /></a>
            </div>
            <div class="logo" id="kawasaki">
            <a href="https://www.tech2roo.com/compat/kawasaki/650/er-6f-sans-abs/161-140-181"><img class="logo" src="https://www.freepnglogos.com/uploads/kawasaki-png-logo/kawasaki-green-emblem-png-logo-1.png" /></a>
            </div>           
            <div class="logo" id="suzuki">
            <a href="https://www.tech2roo.com/compat/suzuki/750/gsr750/153-17-343"><img class="logo" src="https://seeklogo.com/images/S/suzuki-logo-B2B31D667D-seeklogo.com.png" /></a>
            </div>           
            <div class="logo" id="aprilia">
            <a href="https://www.tech2roo.com/compat/aprilia/150/scarabeo/2005/418-150-910-12"><img class="logo" src="https://www.autocollant-tuning.com/2143-home_default/autocollant-aprilia-sport.jpg" /></a>
            </div>                       
            <div class="logo" id="bmw">
            <a href="https://www.tech2roo.com/compat/bmw/1000/r-100-r/1997/1-24-753-77"><img class="logo" src="https://assets.stickpng.com/thumbs/580b57fcd9996e24bc43c46e.png" /></a>
            </div>       
            </div>                
            <p style="text-align: center;"> voir tout les <a href="https://www.tech2roo.com/">constructeurs </a></p>'
        );
}

function typesCss1(){
    echo "
    <style type='text/css'>

.logo{
    width: 300px;
    height: 300px;
    display: flex;
    margin-left: auto

}
#yamaha{
    border: 1px solid blue;
   

}

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

    
    #containerLogo{
        margin: 3%;
        display: flex;
        flex-direction: row;
    }

    
    #triangle{
        width: 1%;
        height: 1%;
        margin-top: 1%;
        margin-left: 50%;
        display: flex;
        flex-direction: row;
        position: center;
        align-self: center;
    }
    </style>
    ";
}


/////////////////////////////////////////larbi///////////////////////////////////////////

function types(){
    printf('
    <div id="containerTitleSelectTypeVehicule">
        <h3 id="titleSelectTypeVehicule"><span>sélectionnez votre type de véhicule</span></h3>
    </div>
    <div class="redArrow">
    <img  src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/Red_triangle.svg/540px-Red_triangle.svg.png" alt="">
</div>

    <div id="container">
        <div id="containerListTypeVehicule">
            <div class="linkImglistTypeVehicule">
            <a class="linkTypeVehicule" href="#">
                <img class="iconSelectTypeVehicule" src="http://imagenspng.com/wp-content/uploads/desenhos-motos-Imagem-png-para-imprimir-gratis-768x768.png" alt="">
                <p>Pièces moto</p>
            </a>
            </div>
            <div class="linkImglistTypeVehicule">
            <a class="linkTypeVehicule" href="#">
                <img class="iconSelectTypeVehicule" src="http://imagenspng.com/wp-content/uploads/desenhos-motos-Imagem-png-para-imprimir-gratis-768x768.png" alt="">
                <p>Pièces scooter</p>
            </a>
            </div>
            <div class="linkImglistTypeVehicule">
            <a class="linkTypeVehicule" href="#">
                <img class="iconSelectTypeVehicule" src="http://imagenspng.com/wp-content/uploads/desenhos-motos-Imagem-png-para-imprimir-gratis-768x768.png" alt="">
                <p>Pièces quad et SSV</p>
            </a>
            </div>
            <div class="linkImglistTypeVehicule">
            <a class="linkTypeVehicule" href="#">
                <img class="iconSelectTypeVehicule" src="http://imagenspng.com/wp-content/uploads/desenhos-motos-Imagem-png-para-imprimir-gratis-768x768.png" alt="">
                <p>Pièces tout terrain</p>
            </a>

            </div>
        </div>
    </div>
    ');
}

function typesCss(){
    echo "
	<style type='text/css'>

    .redArrow{
        width: 1%;
        height: 1%;
        margin-left: auto;
        margin-right: auto;
    }

    #container{
        display: flex;
        justify-content: center;
    }

    #containerTitleSelectTypeVehicule{
        display: flex;
        width:100%;
        justify-content: center;
    }


    #titleSelectTypeVehicule{
        position: relative;
        overflow: hidden;
        text-align: center;
        font-weight: bold;
        font-size: 20px;
        text-transform: uppercase;
        width: 90%;
    }

    #titleSelectTypeVehicule:after, #titleSelectTypeVehicule:before {
        content: '\a0';
        overflow: hidden;
        position: absolute;
        height: 0.8px;
        width: 50%;
        top: 51%;
        margin-left: 1%;
        background: black;
    }

    #titleSelectTypeVehicule:before{
    margin-left: -51%;
    text-align: right;
    }

	#containerListTypeVehicule{
        list-style: none;
        display: flex;
        width: 55%;
        /*justify-content: space-around;*/
        column-gap: 10%;
        margin-left: auto;
        margin-right: auto;
    }

    .linkTypeVehicule{
        outline: none;
        text-decoration: none;
    }

    .linkImglistTypeVehicule{
        display: block;
        width: 25%;
    }

    .linkImglistTypeVehicule p {   
        text-align: center;
        white-space: nowrap;

    }

   

	</style>
	";
}

add_action('wp_footer', 'typesCss');
add_action('wp_footer', 'types');



