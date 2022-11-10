<?php
/**
 * @package test
 * @version 1.0
 */
/*
Plugin Name: Test
Plugin URI: 
Description: plugin d'entrainement
Author: Larbi Chajai
Version: 1.0
Author URI: 
*/

add_action('wp_footer', 'types');


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

    iconSelectTypeVehicule{

    }

	</style>
	";
}

add_action('wp_footer', 'typesCss');

?>