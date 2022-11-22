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

   //infos de connexions à la db
function call_bdd(): PDO{
    try{
        $db = new PDO('mysql:host=localhost;dbname=ukooparts','root','');
        $db -> exec('SET NAMES "UTF8"');
        return $db;
    }catch(PDOException $e){
        echo 'Erreur:'.$e ->getMessage();
        die();
    }
}

    // fonction de display des constructeurs par noms A-Z
function shortcode_manufacturers() {


    $manufacturers = (call_bdd()->query("SELECT * FROM `PREFIX_ukooparts_manufacturer` ORDER BY name ASC;"))->fetchAll();

    $displayManu = "";
    $first_letterManu = $manufacturers[0]['name'][0];
    $displayManu = $displayManu. '<h3>' . $first_letterManu. '</h3><div>';

    foreach($manufacturers as $manufacturer) {
        if($manufacturer['name'][0] != $first_letterManu) {
            $first_letterManu = $manufacturer['name'][0];
            $displayManu = $displayManu. '</div><h3>' .$first_letterManu. '</h3><div>';
            $displayManu = $displayManu.$manufacturer['name'];
        } else {
            $displayManu = $displayManu.$manufacturer['name'];
        }

    }
    $displayManu = $displayManu.'</div>';
    return $displayManu;
}

add_shortcode('manufacturers', 'shortcode_manufacturers');

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
            <a href="models/?manufact_id=11"><img class="logo" src="https://i.pinimg.com/originals/0b/c0/24/0bc024f240e6bec6d29df3155d487adf.png" /></a>
            </div>
            <div class="logo" id="kawasaki">
            <a href="models/?manufact_id=5"><img class="logo" src="https://www.freepnglogos.com/uploads/kawasaki-png-logo/kawasaki-green-emblem-png-logo-1.png" /></a>
            </div>           
            <div class="logo" id="suzuki">
            <a href="models/?manufact_id=9"><img class="logo" src="https://seeklogo.com/images/S/suzuki-logo-B2B31D667D-seeklogo.com.png" /></a>
            </div>           
            <div class="logo" id="aprilia">
            <a href="models/?manufact_id=16"><img class="logo" src="https://www.autocollant-tuning.com/2143-home_default/autocollant-aprilia-sport.jpg" /></a>
            </div>                       
            <div class="logo" id="bmw">
            <a href="models/?manufact_id=15"><img class="logo" src="https://assets.stickpng.com/thumbs/580b57fcd9996e24bc43c46e.png" /></a>
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

function shortcode_cadeaux(): string{
    return "<h2>Bienvenue dans cette surperbe liste de cadeaux ! !</h2>";
}
add_shortcode('cadeaux', 'shortcode_cadeaux');


////////////////////////////////ilyes/////////////////////////////////////////////////////

function shortcode_descriptif(): void{

    if(isset($_GET['descriptif_id']) && isset($_GET['lang_id'])){

        $lang_id = $_GET['lang_id'];
        $descriptif_id = $_GET['descriptif_id'];

        $query = call_bdd() -> query( "SELECT LANG.description AS description, ENGIN.model AS model,ENGIN.id_ukooparts_engine AS id, ENGIN.year_start AS start, ENGIN.year_end AS end, ENGIN.image AS image, MANU.name AS manufacturer, CONCAT(MANU.name, ' ', ENGIN.model) AS title, CONCAT(ENGIN.year_start, '-', ENGIN.year_end) AS years  
        FROM  PREFIX_ukooparts_engine ENGIN 
        inner join PREFIX_ukooparts_engine_lang LANG 
        on LANG.id_ukooparts_engine = ENGIN.id_ukooparts_engine
        INNER JOIN PREFIX_ukooparts_manufacturer MANU 
        ON ENGIN.id_ukooparts_manufacturer = MANU.id_ukooparts_manufacturer WHERE ENGIN.id_ukooparts_engine = $descriptif_id AND LANG.id_lang = $lang_id");

        foreach($query as $row)
        {
            echo("<h1>" . $row['title'] . "</h1> 
                  <h2>" . $row['years'] . "</h2>
                  <p>" . $row['description'] . "</p>"
            );

        }

    }
}
add_shortcode('descriptif', 'shortcode_descriptif');

// yuan
function shortcode_models() {
    $html= '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            </head>
            <style>
            </style>
            <body>
                <div class="container">
                    <form method="post" action="">
                        <input type="text" placeholder="TOUS pour tous les modèles" name="key">
                        <input type="submit" name="submit" value="Rechercher">
                    </form>
                </div>';
    // if manufacturer id set in url
    if(isset($_GET['manufact_id'])){
        // if engine type id is not set in url, the list of models will be filtered only by manufacturer(brand name: example YAMAHA)
        if(!isset($_GET['engine_type_id'])){
            $manufact_id = $_GET['manufact_id'];

            $models = (call_bdd()->query("SELECT distinct engine.model, manu.name
                FROM PREFIX_ukooparts_engine AS engine
                INNER JOIN PREFIX_ukooparts_manufacturer AS manu
                ON manu.id_ukooparts_manufacturer = engine.id_ukooparts_manufacturer
                WHERE  manu.id_ukooparts_manufacturer=$manufact_id ORDER BY model ASC;"))->fetchAll();
        // if engine type id is set, the list of models will be filtered by both manufacturer(brand name: example YAMAHA) and engine type id(type of vehicle: example scotter)
        } else {
                $manufact_id = $_GET['manufact_id'];
                $engine_type_id = $_GET['engine_type_id'];

                $models = (cal_from_jd()->query("SELECT distinct engine.model, manu.name, type.name AS type_name
                    FROM PREFIX_ukooparts_engine AS engine
                    INNER JOIN PREFIX_ukooparts_manufacturer AS manu
                    ON manu.id_ukooparts_manufacturer = engine.id_ukooparts_manufacturer
                    INNER JOIN PREFIX_ukooparts_engine_type_lang AS type
                    ON type.id_ukooparts_engine_type = engine.id_ukooparts_engine_type
                    WHERE  manu.id_ukooparts_manufacturer=$manufact_id AND engine.id_ukooparts_engine_type=$engine_type_id ORDER BY model ASC;"))->fetchAll();
        }
        // if there is no search, or the search key word is 'tous', or search without any key word entered, the whole list will be showed
        if($models && (!isset($_POST['submit']) || (isset($_POST['submit']) && (strtoupper($_POST['key'])=='TOUS' || !$_POST['key']) ))     ) {
            $first_letter = $models[0]['model'][0];
            $html = $html.'<h3>'.$first_letter.'</h3><div>';
            foreach($models as $model){
                if($model['model'][0] != $first_letter){
                    $first_letter = $model['model'][0];
                    $html = $html.'</div><h3>'.$first_letter.'</h3><div>';
                    $html = $html.$model['name'].' '.$model['model'].',  ';
                }else{
                    $html = $html.$model['name'].' '.$model['model'].',  ';
                }
            }
            return $html.'</div></body></html>';
        // if the search key word is not 'tous', the list will be filtered by the key words
        } else if ($models && isset($_POST['submit']) && strtoupper($_POST['key']) !='TOUS'){
            $key = $_POST['key'];
            $array_models_found = array();
            $html = $html.'<div>';
            foreach($models as $model){
                if(str_contains(strtoupper($model['model']), strtoupper($key)) !== false){
                    $html = $html.$model['name'].' '.$model['model'].', ';
                    array_push($array_models_found, $model);
                }
            }
            if(sizeof($array_models_found) == 0){
                $html = $html.'Ce modèle ne existe pas';
            }
            return $html.'</div></body></html>';
        }
    // if no id is set in url, no list is shown
    } else if (!isset($_GET['manufact_id']) && !isset($_GET['engine_type_id'])){
        return $html.'<div>Non manufacturer choisi</div></body></html>';
    }
}
add_shortcode('models', 'shortcode_models');

// Larbi top50 moto
function shortcode_topmoto(): string{
    try{
        $db = new PDO('mysql:host=localhost;dbname=ukooparts','root','');
        $db -> exec('SET NAMES "UTF8"');
        $motoData = $db->query("SELECT * FROM PREFIX_ukooparts_engine_lang LIMIT 50")->fetchAll(PDO::FETCH_ASSOC);

        $string = "";
        $string .= "<ol id='order_list_vehicle'>";
        foreach ($motoData as $moto) {
            $string .= "<li class='list_vehicle'>" . $moto["meta_title"] . "</li>"; 
        }
        $string .= "<a href='#'><li>voir toutes les motos</p></li>";
        $string .= "</ol>";

        return $string;
    }catch(PDOException $e){
        echo 'Erreur:'.$e ->getMessage();
        die();
    }
    
    return "<div>Aucune moto trouvé</div>";
}
add_shortcode('topmoto', 'shortcode_topmoto');

/*************      Test CSS  ********/

add_action('wp_enqueue_scripts', 'call_top50_assets');

function call_top50_assets():void{
    load_assets('css/ukooparts.css');
}

function load_assets($path): void
{

    wp_enqueue_style(
        'ukooparts',
        plugin_dir_url(__FILE__) . $path,
        array(),
        1,
        'all'
    );

}
