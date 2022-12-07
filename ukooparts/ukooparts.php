<?php
use Automattic\WooCommerce\Admin\API\Reports\Query;

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
require UKOOPARTS_PLUGIN_DIR . 'bdd.php'; // contains ukoo tables sql info

$plugin = new Ukoo\Ukooparts\UkooPartsPlugin(__FILE__);

add_action('wp_head', 'import_script');

function import_script(){
    echo "
    <link href=\"".plugin_dir_url(__FILE__)."css/footer_manufacturers.css\" rel=\"stylesheet\">
    <link href=\"".plugin_dir_url(__FILE__)."css/footer_types.css\" rel=\"stylesheet\">
    <link href=\"".plugin_dir_url(__FILE__)."css/top50.css\" rel=\"stylesheet\">
    <link href=\"".plugin_dir_url(__FILE__)."node_modules/bootstrap/dist/css/bootstrap.min.css\" rel=\"stylesheet\">
    <script type=\"text/javascript\" src=\"".plugin_dir_url(__FILE__)."node_modules/bootstrap/dist/js/bootstrap.min.js\"></script>
    ";
}

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
// to create and insert ukoo tables
// call_bdd()->query($query);

    // fonction de display des constructeurs par noms A-Z
function shortcode_manufacturers() {

    $displayManu = '<div>
                        <form method="post" action="">
                            <input type="text" placeholder="TOUS pour tous les marques" name="key">
                            <input type="submit" name="submit" value="Rechercher">
                        </form>
                    </div>';

    if(isset($_SESSION['id_manufacturer'])){ // if a vehicle is chosen in the header, find only the chosen manufacturer in the bdd
        $id_manu = $_SESSION['id_manufacturer'];
        $manufacturers = (call_bdd()->query("SELECT * FROM PREFIX_ukooparts_manufacturer WHERE id_ukooparts_manufacturer= $id_manu;"))->fetchAll();
    } else {
        if(!isset($_GET['engine_type_id'])){ // if a vehicle type is not chosen, find all the manufacturers 
            $manufacturers = (call_bdd()->query("SELECT * FROM `PREFIX_ukooparts_manufacturer` ORDER BY name ASC;"))->fetchAll();
        }else{ // vehicle type is chosen, find only the manufacturers of the chosen type
            $engine_type_id = $_GET['engine_type_id']; // ici recup premiere lettre 
            $manufacturers = (call_bdd()->query("select distinct engine.id_ukooparts_manufacturer, engine.id_ukooparts_engine_type, manu.name
                FROM PREFIX_ukooparts_engine as engine
                INNER JOIN PREFIX_ukooparts_manufacturer as manu
                ON manu.id_ukooparts_manufacturer = engine.id_ukooparts_manufacturer
                WHERE engine.id_ukooparts_engine_type = $engine_type_id ORDER BY name ASC"))->fetchAll();
        }
    }    

    // ici le code display_AZ pour recuperer l'info manufacturer filtrée ou non
    $first_letter_manufacturer = $manufacturers[0]['name'][0]; 
    //déclaration de deux arrays, un pour les lettres et un pour les chiffres
    $array_manufacturer_list_letters = array();
    $manufacturer_first_number = array();
    //on ne vérifie que si la première donnée est numérique ou non
    if(is_numeric($first_letter_manufacturer)) {
        array_push($manufacturer_first_number, $first_letter_manufacturer);
    } else {
        array_push($array_manufacturer_list_letters, $first_letter_manufacturer);
    }

            // loop pour compléter l'array avec tous les autres caractères sans distinction
    foreach($manufacturers as $manufacturer) {
        if($manufacturer['name'][0] != $first_letter_manufacturer) {
            $first_letter_manufacturer = $manufacturer['name'][0];
            array_push($array_manufacturer_list_letters, $first_letter_manufacturer);
        }
    } 
    // s'il y au moins UN chiffre, le 0-9 est activé et cliquable
    if (count($manufacturer_first_number) > 0 ) { 
        $displayManu.="<span><a href=\"#0-9\" style='color:black;'>0-9</a></span>";
    } else {
        $displayManu.="<span><a style='color:grey;'>0-9</a></span>";
    }
    // on display l'aphabet, en vérifiant si chaque lettre est présente dans la liste manufacturers
    // si oui, la lettre est noire et cliquable avec ancre.
    foreach(range('A', 'Z') as $abc) {
        if (in_array($abc, $array_manufacturer_list_letters) ) {
            $displayManu.="<span><a href=\"#$abc\" style='color:black;'>$abc</a></span>";
        } else {
            $displayManu.="<span style='color:grey;'>$abc</span> ";
        }
    }
    
    // fin du code display_AZ
    $displayManu .= '<div>';
    if($manufacturers && (!isset($_POST['submit']) || (isset($_POST['submit']) && (strtoupper($_POST['key'])=='TOUS' || !$_POST['key']) ))) {
        $first_letterManu = $manufacturers[0]['name'][0];
        $displayManu .= '<h3 id="'.$first_letterManu.'">' . $first_letterManu. '</h3><div>'; // echo $first_letterManu pour créer une ancre unique en fonction de la lettre
            // cherche la premiere lettre est differente
        foreach($manufacturers as $manufacturer) {
            $manufact_id = $manufacturer['id_ukooparts_manufacturer'];
            if($manufacturer['name'][0] != $first_letterManu) {
                $first_letterManu = $manufacturer['name'][0];
                //  le if vérifie s'il s'agit d'un numéro en initiale et redirige tout vers la dernière génération de l'id"0-9". 
                if(!is_numeric($first_letterManu)) {
                    $displayManu.= '<div><h3 id="'.$first_letterManu.'">' .$first_letterManu. '</h3></div>'; // echo $first_letterManu pour créer une ancre unique en fontion de la lettre
                } else {
                    $displayManu.= '<div><h3 id="0-9">' .$first_letterManu. '</h3></div>'; // tous les chiffres forment des sections différentes mais une seul id
                }
        
                if(isset($_GET['engine_type_id'])){
                    $displayManu.= '<a href="models/?manufact_id='.$manufact_id.'&engine_type_id='.$_GET['engine_type_id'].'">';
                    $displayManu.= '<img src="../wp-content/plugins/ukooparts/views/images/manufacturer/' .$manufact_id. '.jpg"/>';
                    $displayManu.='</a>';
                }else{
                    $displayManu.= '<a href="models/?manufact_id='.$manufact_id.'">';
                    $displayManu.= '<img src="../wp-content/plugins/ukooparts/views/images/manufacturer/' .$manufact_id. '.jpg"/>';
                    $displayManu.= '</a>';
                }
            } else {
                if(isset($_GET['engine_type_id'])){
                    $displayManu.= '<a href="models/?manufact_id='.$manufact_id.'&engine_type_id='.$_GET['engine_type_id'].'">';
                    $displayManu.= '<img src="../wp-content/plugins/ukooparts/views/images/manufacturer/' .$manufact_id. '.jpg"/>';
                    $displayManu.= '</a>';
                
                }else{
                    $displayManu.= '<a href="models/?manufact_id='.$manufact_id.'">';
                    $displayManu.=  '<img src="../wp-content/plugins/ukooparts/views/images/manufacturer/' .$manufact_id. '.jpg"/>';
                    $displayManu.= '</a>';
                    
                }
            }
        }
        
    }else if ($manufacturers && isset($_POST['submit']) && strtoupper($_POST['key']) !='TOUS'){
        $key = $_POST['key'];
        $array_manufacts_found = array();
        $displayManu = $displayManu.'<div>';
        foreach($manufacturers as $manufacturer){
            $manufact_id = $manufacturer['id_ukooparts_manufacturer'];
            if(str_contains(strtoupper($manufacturer['name']), strtoupper($key))){
                if(isset($_GET['engine_type_id'])){
                    $displayManu = $displayManu.'<a href="parts-models/?manufact_id='.$manufact_id.'&engine_type_id='.$_GET['engine_type_id'].'">'.$manufacturer['name'].'</a>, ';
                }else{
                    $displayManu = $displayManu.'<a href="parts-models/?manufact_id='.$manufact_id.'">'.$manufacturer['name'].'</a>, ';
                }
                array_push($array_manufacts_found, $manufacturer);
            }
        }
        if(sizeof($array_manufacts_found) == 0){
            $displayManu = $displayManu.'Ce modèle n\'existe pas';
        }
    }
    $displayManu.='</div>';
    return $displayManu;
}
add_shortcode('manufacturers', 'shortcode_manufacturers');

add_action('wp_footer','marque');

function marque(): void{
    printf(' <div class="titrediv" id="titrecss">
            <h2 id="titre"><span> Nos constructeurs moto route </span></h2>
            </div>
            <div class="triangle_container">
            <img id="triangle" src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/Red_triangle.svg/540px-Red_triangle.svg.png" />
            </div>
            <div class="container" id="containerLogo">
            <div class="logo" id="yamaha">
            <a href="models/?manufact_id=11"><img class="logo" src="http://localhost/ukooparts/wp-content/uploads/2022/12/11.jpg" /></a>
            </div>
            <div class="logo" id="kawasaki">
            <a href="models/?manufact_id=5"><img class="logo" src="http://localhost/ukooparts/wp-content/uploads/2022/12/5.jpg" /></a>
            </div>           
            <div class="logo" id="suzuki">
            <a href="models/?manufact_id=9"><img class="logo" src="http://localhost/ukooparts/wp-content/uploads/2022/12/9.jpg" /></a>
            </div>           
            <div class="logo" id="aprilia">
            <a href="models/?manufact_id=16"><img class="logo" src="http://localhost/ukooparts/wp-content/uploads/2022/12/16.jpg" /></a>
            </div>                       
            <div class="logo" id="bmw">
            <a href="models/?manufact_id=15"><img class="logo" src="http://localhost/ukooparts/wp-content/uploads/2022/12/15.jpg" /></a>
            </div>       
            </div>                
            <p style="text-align: center;"> voir tout les <a href="manufacturers">constructeurs </a></p>'
        );
}


function types():void{

    $type_vehicule_Db = call_bdd()->query("SELECT name FROM `PREFIX_ukooparts_engine_type_lang` WHERE id_lang = 1");
    printf('<div id="containerTitleSelectTypeVehicule">
        <h3 id="titleSelectTypeVehicule"><span>sélectionnez votre type de véhicule</span></h3>
        </div>
        <div class="triangle_container">
            <img id="triangle" src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/Red_triangle.svg/540px-Red_triangle.svg.png" />
        </div>');

    foreach ($type_vehicule_Db as $type_vehicule) {
        printf('<div id="container">
                    <div id="containerListTypeVehicule">
                        <div class="linkImglistTypeVehicule">
                        <a class="linkTypeVehicule" href="manufacturers/?engine_type_id=1">
                            <img class="iconSelectTypeVehicule" src="http://imagenspng.com/wp-content/uploads/desenhos-motos-Imagem-png-para-imprimir-gratis-768x768.png" alt="">
                            <p>' .  $type_vehicule["name"] . '</p>
                        </a>
                    </div>
                </div>');
    }; 
}

add_action('wp_footer', 'types');

function shortcode_cadeaux(): string{
    return '<a href="#" class="alert-warning">Warning link</a>';
}
add_shortcode('cadeaux', 'shortcode_cadeaux');


function shortcode_descriptif(){
    $html = '';
    if(isset($_GET['engine_id'])){
        $engine_id = $_GET['engine_id'];
        // to get model info
        $query = (call_bdd() -> query( "SELECT distinct TYPE_LANG.name as type_name, LANG.description AS description, ENGIN.model AS model,ENGIN.id_ukooparts_engine, ENGIN.year_start AS start, ENGIN.year_end AS end, ENGIN.image AS image, MANU.name AS manufacturer, CONCAT(MANU.name, ' ', ' ',ENGIN.model) AS title, CONCAT(ENGIN.year_start, '-', ENGIN.year_end) AS years  
            FROM  PREFIX_ukooparts_engine ENGIN 
            INNER JOIN PREFIX_ukooparts_engine_lang LANG 
            on LANG.id_ukooparts_engine = ENGIN.id_ukooparts_engine
            INNER JOIN PREFIX_ukooparts_manufacturer MANU 
            ON ENGIN.id_ukooparts_manufacturer = MANU.id_ukooparts_manufacturer 
            INNER JOIN PREFIX_ukooparts_engine_type_lang AS TYPE_LANG 
            ON ENGIN.id_ukooparts_engine_type = TYPE_LANG.id_ukooparts_engine_type
            WHERE ENGIN.id_ukooparts_engine = $engine_id AND LANG.id_lang = 1;"))->fetchAll();
        // main categories of the page fiche-discriptif 
        $categories = call_bdd() -> query("select distinct wptt.parent AS term_id, wpt.name
            from wp_term_taxonomy wptt
            INNER JOIN wp_termmeta wptm
            ON wptm.term_id = wptt.parent
            INNER JOIN wp_terms wpt
            on wpt.term_id = wptm.term_id;");
            // query to find all products(accessoires) and their category, parent category and Model vehicle
        $model_name = $query[0]['model'];
        // to get all the accessoires(products) with details
        $model_products = (call_bdd()->query("SELECT distinct wpp.ID as product_id, wpp.post_author, wpp.post_title, wpp.post_status, wpp.post_type,
            wpt.term_id, wpt.name AS term_name, wptt.parent, wptm.meta_value
            FROM wp_posts wpp
            INNER JOIN wp_term_relationships wptr
            ON wptr.object_id = wpp.ID
            INNER JOIN wp_term_taxonomy wptt
            ON wptt.term_taxonomy_id = wptr.term_taxonomy_id
            INNER JOIN wp_terms wpt
            ON wpt.term_id = wptt.term_id
            INNER JOIN wp_termmeta wptm
            ON wptm.term_id = wpt.term_id
            WHERE wpp.post_type = 'product'
            AND wpp.post_status = 'publish'
            AND wptr.term_taxonomy_id != wpp.post_author
            AND wptm.meta_key = 'display_type';"))->fetchAll();   
        // to dislay model info
        foreach($query as $row)
        {
            $html = $html."<h3>" . $row['title'] . "</h3> 
                <h4>" . $row['years'] . "</h4>
                <p>" . $row['description'] . "</p>";
        }
        // get accessoires(products) ids of current model
        $list_model_product_ids = array();
        foreach($model_products as $product){
            if($product['term_name'] == $model_name){
                array_push($list_model_product_ids, $product['product_id']);
            }
        }

        foreach($categories as $category){
            $parent_category_id = $category['term_id'];
            // sub categories of each category
            $sub_categories = call_bdd() -> query("SELECT wptm.term_id, wptm.meta_value, wpt.name, wptxm.parent
                FROM wp_termmeta wptm
                LEFT JOIN wp_terms wpt
                ON wpt.term_id = wptm.term_id
                LEFT JOIN wp_term_taxonomy wptxm
                ON wptxm.term_id = wpt.term_id
                WHERE wptm.meta_value = 'subcategories'
                AND wptxm.parent = $parent_category_id;");
                // to display each category name
            $html = $html. "<ul>
                    <li>".$category['name']. "</li> 
                    <li>
                        <ul>";
                            foreach($sub_categories as $sub_category){
                                $term_id = $sub_category['term_id'];
                                $list_products = array();
                                foreach($model_products as $product){
                                    // if this product belong to this sub category and belong to this model
                                    if(($product['term_id'] == $sub_category['term_id']) && in_array($product['product_id'], $list_model_product_ids)){
                                        array_push($list_products, $product);
                                    }
                                } 
                                // if list of products has at least 1 item, show this sub category
                                $sub_category_id = $sub_category['term_id'];
                                if(sizeof($list_products)>0){
                                    $html = $html.'<li><a href="list-accessoires/?engine_id='.$engine_id.'&sub_category_id='.$sub_category_id.'">'.$sub_category['name'].'('.sizeof($list_products).')'.'</a></li>';

                                }
                            }
                        
                        $html = $html. "</ul>
                    </li>
                </ul>";
        }
    }
    return $html;
}
add_shortcode('descriptif', 'shortcode_descriptif');


function shortcode_search(): void{

    if(isset($_GET['$query'])){
        $query = call_bdd()->query( "SELECT LANG.description AS description, ENGIN.model AS model,ENGIN.displacement as cylindré, ENGIN.year_start AS start, ENGIN.year_end AS end, ENGIN.image AS image, MANU.name AS manufacturer,
        CONCAT(MANU.name, ' ', ENGIN.model) AS title FROM PREFIX_ukooparts_engine_lang LANG 
        INNER JOIN PREFIX_ukooparts_engine ENGIN on LANG.id_ukooparts_engine = ENGIN.id_ukooparts_engine 
        INNER JOIN PREFIX_ukooparts_manufacturer MANU ON ENGIN.id_ukooparts_manufacturer = MANU.id_ukooparts_manufacturer;");

        foreach($query as $row)
        {
            echo("<h1>" . $row['title'] . "</h1> 
                <h2>" . $row['start'] . "</h2>
                <p>" . $row['cylindré'] . "</p>");
        }

    }
}
add_shortcode('search', 'shortcode_search');



// yuan
function shortcode_models(): string {
    $html = null;
    // if manufacturer id set in url
    if(isset($_SESSION['id_engine']) || isset($_GET['manufact_id'])){
        if(isset($_SESSION['id_engine'])){
            $id_engine = $_SESSION['id_engine'];
            $models = (call_bdd()->query("SELECT engine.model, manu.name, engine.id_ukooparts_engine AS id_engine, CONCAT(manu.name, ' ', engine.model) AS display_name
                FROM PREFIX_ukooparts_engine AS engine
                INNER JOIN PREFIX_ukooparts_manufacturer AS manu
                ON manu.id_ukooparts_manufacturer = engine.id_ukooparts_manufacturer
                WHERE  engine.id_ukooparts_engine=$id_engine;"))->fetchAll();
        // if engine type id is not set in url, the list of models will be filtered only by manufacturer(brand name: example YAMAHA)
        }else if(!isset($_GET['engine_type_id'])){
            $manufact_id = $_GET['manufact_id'];

            $models = (call_bdd()->query("SELECT engine.model, manu.name, engine.id_ukooparts_engine AS id_engine, CONCAT(manu.name, ' ', engine.model) AS display_name
                FROM PREFIX_ukooparts_engine AS engine
                INNER JOIN PREFIX_ukooparts_manufacturer AS manu
                ON manu.id_ukooparts_manufacturer = engine.id_ukooparts_manufacturer
                WHERE  manu.id_ukooparts_manufacturer=$manufact_id ORDER BY model ASC;"))->fetchAll();
        // if engine type id is set, the list of models will be filtered by both manufacturer(brand name: example YAMAHA) and engine type id(type of vehicle: example scotter)
        } else {
                $manufact_id = $_GET['manufact_id'];
                $engine_type_id = $_GET['engine_type_id'];

                $models = (call_bdd()->query("SELECT engine.model, manu.name, type.name AS type_name, engine.id_ukooparts_engine AS id_engine, CONCAT(manu.name, ' ', engine.model) AS display_name, CONCAT(substr(type.name, 8), ' ', manu.name) AS type_manu_name
                    FROM PREFIX_ukooparts_engine AS engine
                    INNER JOIN PREFIX_ukooparts_manufacturer AS manu
                    ON manu.id_ukooparts_manufacturer = engine.id_ukooparts_manufacturer
                    INNER JOIN PREFIX_ukooparts_engine_type_lang AS type
                    ON type.id_ukooparts_engine_type = engine.id_ukooparts_engine_type
                    WHERE  manu.id_ukooparts_manufacturer=$manufact_id AND engine.id_ukooparts_engine_type=$engine_type_id ORDER BY model ASC;"))->fetchAll();
        }

        if(isset($models[0]['type_manu_name'] )){
            $type= $models[0]['type_manu_name'];
        }else{
            $type=$models[0]['name'];
        }

        //code display_AZ_models
            // récupère la première lettre des modèles dispo après filtrage sql
        $first_letter = $models[0]['model'][0];
        $array_models_list_letters = array();
        $model_first_number = array();
            // on vérifie si la première donnée est numérique ou non
        if(is_numeric($first_letter)) {
            array_push($model_first_number, $first_letter);
        } else {
            array_push($array_models_list_letters, $first_letter);
        }

            // loop pour compléter l'array avec tous les autres caractères
        foreach($models as $model){
            if($model['model'][0] != $first_letter){
                $first_letter = $model['model'][0];
                array_push($array_models_list_letters, $first_letter);
            }
        }
            // s'il y a un caractère dans l'array, on active l'ancre "0-9"
        if(count($model_first_number) > 0) {
            ?><span><a href="#0-9" style='color:black;'><?php echo " 0-9" ?></a></span><?php 
        } else {
            ?><span><a style='color:grey;'><?php echo " 0-9" ?></a></span><?php 
        }

            //compare les 2 arrays. $abcModels et permet de créer un href
        foreach(range('A', 'Z') as $abcModels) {
            if (in_array($abcModels, $array_models_list_letters)) { ?>
                <span><a href="#<?php echo $abcModels ?>" style='color:black;'><?php echo $abcModels ?><a></span><?php
            } else {
                echo "<span style='color:grey;'>$abcModels</span>";
            }
        }
        //fin du code display_AZ_models

        $html= '
                <div class="divHeaderTypeModel">
                    <div>
                            <h3 class="titleDivHeaderTypeModel">pièces détachées et accessoires '.$type.'</h3>
                            <p class="paraDivHeaderTypeModel">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et 
                                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip 
                                ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu 
                                fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt 
                                mollit anim id est laborum.
                            </p>
                        </div>
                        <div>
                            <img src="https://i.pinimg.com/originals/0b/c0/24/0bc024f240e6bec6d29df3155d487adf.png"/>
                            <p class="paraDivHeaderTypeModel"><a href="#">Accéder au site de la marque</a></p>
                        </div>
                    </div>
                    
                    <form method="post" action="">
                        <input type="text" placeholder="TOUS pour tous les modèles" name="key">
                        <input type="submit" name="submit" value="Rechercher">
                    </form>
                </div>
        ';


        // if there is no search, or the search key word is 'tous', or search without any key word entered, the whole list will be showed
        if($models && (!isset($_POST['submit']) || (isset($_POST['submit']) && (strtoupper($_POST['key'])=='TOUS' || !$_POST['key']) ))) {
            $first_letter = $models[0]['model'][0];
            if(!is_numeric($first_letter)) {
                $html = $html.'<h3 id="'.$first_letter.'">'.$first_letter.'</h3><div>'; // echo $first_letter pour créer une ancre
            } else {
                $html = $html.'<h3 id="0-9">'.$first_letter.'</h3><div>';
            }

            foreach($models as $model){
                if($model['model'][0] != $first_letter){
                    $first_letter = $model['model'][0];

                    if(!is_numeric($first_letter)) {
                            $html = $html.'</div><h3 id="'.$first_letter.'">'.$first_letter.'</h3><div>'; //echo $first_letter pour créer une ancre
                        } else {
                            $html = $html.'</div><h3 id="0-9">'.$first_letter.'</h3><div>'; //echo $first_letter pour créer une ancre
                        }

                        $html = $html.'<a href="fiche-descriptif/?engine_id='.$model['id_engine'].'">'.$model['display_name'].'</a>  ';
                }else{
                    $html = $html.'<a href="fiche-descriptif/?engine_id='.$model['id_engine'].'">'.$model['display_name'].'</a>  ';
                }
            }
            return $html.'</div>';
        // if the search key word is not 'tous', the list will be filtered by the key words
        } else if ($models && isset($_POST['submit']) && strtoupper($_POST['key']) !='TOUS'){
            $key = $_POST['key'];
            $array_models_found = array();
            $html = $html.'<div>';
            foreach($models as $model){
                if(str_contains(strtoupper($model['model']), strtoupper($key))){
                    $html = $html.'<a href="fiche-descriptif/?engine_id='.$model['id_engine'].'">'.$model['display_name'].'</a>  ';
                    array_push($array_models_found, $model);
                }
            }
            if(sizeof($array_models_found) == 0){
                $html = $html."Ce modèle n'existe pas";
            }
            return $html.'</div>';
        }
    // if no id is set in url, no list is shown
    } else if (!isset($_GET['manufact_id']) && !isset($_GET['engine_type_id'])){
        return $html.'<div>Non manufacturer choisi</div>';
    }

    return $html;
}
add_shortcode('models', 'shortcode_models');

// Larbi top50 moto
function shortcode_topmoto(): string{
    try{
        $motoData = call_bdd()->query("SELECT * FROM PREFIX_ukooparts_engine_lang LIMIT 50")->fetchAll(PDO::FETCH_ASSOC);
        $string = "";
        $string .= "<ol id='order_list_vehicle'>";
        foreach ($motoData as $moto) {
            $string .= "<li class='list_vehicle'><a href='fiche-descriptif/?engine_id=$moto[id_ukooparts_engine]'>" . $moto["meta_title"] . "</a></li>"; 
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
///////////////////////////adam//////////////////

function droplist() {
	include( 'wp-content/plugins/droplist.php' );
}
add_action( 'wp_head', 'droplist' );

//page list accessoire d'un category
function shortcode_list_accessoires(){
    $html = '';
    $engine_id = $_GET['engine_id'];
    if(isset($_GET['engine_id']) && isset($_GET['sub_category_id'])){
        $model = (call_bdd()->query("SELECT distinct engine.id_ukooparts_engine, engine.model, CONCAT(substr(type.name, 8), ' ', manu.name) AS name
            FROM PREFIX_ukooparts_engine engine
            INNER JOIN PREFIX_ukooparts_manufacturer manu
            ON manu.id_ukooparts_manufacturer=engine.id_ukooparts_manufacturer
            INNER JOIN PREFIX_ukooparts_engine_type_lang type
            ON type.id_ukooparts_engine_type=engine.id_ukooparts_engine_type
            WHERE engine.id_ukooparts_engine=$engine_id;"))->fetchAll(); 
            
        $model_products = (call_bdd()->query("SELECT distinct wpp.ID as product_id, wpp.post_author, wpp.post_title, wpp.post_status, wpp.post_type,
            wpt.term_id, wpt.name AS term_name, wptt.parent, wptm.meta_value
            FROM wp_posts wpp
            INNER JOIN wp_term_relationships wptr
            ON wptr.object_id = wpp.ID
            INNER JOIN wp_term_taxonomy wptt
            ON wptt.term_taxonomy_id = wptr.term_taxonomy_id
            INNER JOIN wp_terms wpt
            ON wpt.term_id = wptt.term_id
            INNER JOIN wp_termmeta wptm
            ON wptm.term_id = wpt.term_id
            WHERE wpp.post_type = 'product'
            AND wpp.post_status = 'publish'
            AND wptr.term_taxonomy_id != wpp.post_author
            AND wptm.meta_key = 'display_type';"))->fetchAll(); 

         // get accessoires(products) ids of current model
        $model_manu = $model[0]['name'];
        $list_model_product_ids = array();
        $model_name = $model[0]['model'];
        $sub_category_id = $_GET['sub_category_id'];
        $sub_category_name = '';
        foreach($model_products as $product){
            if($product['term_name'] == $model_name){
                array_push($list_model_product_ids, $product['product_id']);
            }
            if($product['term_id'] == $sub_category_id){
                $sub_category_name = $product['term_name'];
            }
        }
        $html = $html.'<div>'.$sub_category_name.' pour '.$model_manu.'</div>';
        
        foreach($model_products as $product){
            // if this product belong to this sub category and belong to this model
            if(($product['term_id'] == $sub_category_id) && in_array($product['product_id'], $list_model_product_ids)){
                $product_id=$product['product_id'];
                $html = $html.'<div><a href="accessoire/?product_id='.$product_id.'">'.$product['post_title'].'</a></div>';
            }
        }
    }
    return $html;
}    
add_shortcode('list_accessoires', 'shortcode_list_accessoires');

// page accessoire
function shortcode_accessoire(){
    $html = '';
    if(isset($_GET['product_id'])){
        $product_id = $_GET['product_id'];
        // get this product de la bdd
        $product = (call_bdd()->query("SELECT wpp.ID, wpp.post_content, wpp.post_title, wppm.meta_value
            FROM wp_posts wpp
            INNER JOIN wp_postmeta wppm
            ON wpp.ID = wppm.post_id
            WHERE wppm.meta_key = '_regular_price'
            AND wpp.ID = $product_id;"))->fetchAll(); 
        $html = $html.'<div>'.$product[0]['post_title'].'</div>
                <h3>'.$product[0]['post_content'].'</h3>
                <div>'.$product[0]['meta_value'].'€</div>';
        
    }
    return $html;
}    
add_shortcode('accessoire', 'shortcode_accessoire');
///////////////////////Adam Garage///////////////////////////////////////////////////////

function shortcode_garage(){

    $html = '<h2>Historique de recherche :</h2>';


    $query = call_bdd() -> query('SELECT DISTINCT MANU.id_ukooparts_manufacturer,MANU.name,
    ENGIN.id_ukooparts_engine, ENGIN.model as modele FROM PREFIX_ukooparts_engine ENGIN 
    inner join PREFIX_ukooparts_manufacturer MANU ON ENGIN.id_ukooparts_manufacturer
    = MANU.id_ukooparts_manufacturer WHERE ENGIN.id_ukooparts_engine IN
    (SELECT id_ukooparts_engine FROM  PREFIX_ukooparts_customer_engine)');

    $model = $query -> fetchAll();
// var_dump($model);
$html = $html.'<h3>Nom</h3>';
    foreach ($model as $mod){
        $html = $html.'<div>'.$mod['name'].'</div>';
    }
    $html = $html.'<h3>Modèle</h3>';
    foreach ($model as $mod){

        $html = $html.'<div>'.$mod['modele'].'</div>';
    }
    
    
    return $html;

}

add_shortcode('mon-garge', 'shortcode_garage');
