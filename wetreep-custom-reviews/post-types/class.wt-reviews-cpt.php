<?php

if( ! class_exists( 'WT_Reviews_post_type' ) ){
    class WT_Reviews_post_type{

        public function __construct(){

            add_action( 'init', array( $this, 'create_post_type' ) );

            add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

            add_action( 'save_post', array( $this, 'save_post' ) );

        }

        public function save_post( $post_id ){
            if( isset( $_POST['wt_reviews_rating_nonce_action'] ) ){
                if( !wp_verify_nonce( $_POST['wt_reviews_rating_nonce_action'], 'wt_reviews_rating_nonce_action' ) ){
                    return;
                }
            }

            if( defined( "DOING_AUTOSAVE" ) && DOING_AUTOSAVE ){
                return;
            }

            if( isset( $_POST['action'] ) && $_POST['action']  == 'editpost' ){
                $old_rating = get_post_meta( $post_id, 'rating', true );
                $new_rating = $_POST['wt_rating'];

                if( empty( $new_rating ) ){
                    update_post_meta( $post_id, 'rating', 0, $old_rating );
                } else {
                    update_post_meta( $post_id, 'rating', $new_rating, $old_rating );
                }

            }

        }

        public function add_meta_boxes(){
            add_meta_box( 'wt_reviews_rating', __('Rating', 'wetreep-custom-reviews'), array( $this, 'rating_meta_box' ), 'wt_reviews', 'normal', 'default' );
        }

        public function rating_meta_box( $post ){
            wp_enqueue_script( 'admin-reviews-rating' );
            wp_enqueue_style( 'admin-rating-style' );
            wp_enqueue_script( 'admin-main' );
            wp_enqueue_style( 'admin-style' );
            require_once( WT_REVIEWS_PATH . 'views/wt_reviews_rating_metabox.php' );
        }

        public function create_post_type(){
            register_post_type( 'wt_reviews',
                array(
                    'label' => esc_html__('Profile Reviews', 'wetreep-custom-reviews'),
                    'description' => esc_html__('Custom trip reviews for weetrip', 'wetreep-custom-reviews'),
                    'labels' => array(
                        'name' => __( 'Profile reviews', 'wetreep-custom-reviews' ),
                        'singular_name' => __( 'Review', 'wetreep-custom-reviews' ),
                        'add_new' => __( 'Add New', 'wetreep-custom-reviews' ),
                        'add_new_item' => __( 'Add New Review', 'wetreep-custom-reviews' ),
                        'edit_item' => __( 'Edit Review', 'wetreep-custom-reviews' ),
                        'new_item' => __( 'New Review', 'wetreep-custom-reviews' ),
                        'view_item' => __( 'View Review', 'wetreep-custom-reviews' ),
                        'search_items' => __( 'Search Reviews', 'wetreep-custom-reviews' ),
                        'not_found' => __( 'No Reviews found', 'wetreep-custom-reviews' ),
                        'not_found_in_trash' => __( 'No Reviews found in Trash', 'wetreep-custom-reviews' ),
                        'parent_item_colon' => __( 'Parent Review', 'wetreep-custom-reviews' ),
                        'menu_name' => __( 'Reviews', 'wetreep-custom-reviews' ),
                    ),
                    'public' => true,
                    'has_archive' => true,
                    'rewrite' => array( 'slug' => 'reviews' ),
                    'supports' => array( 'title', 'editor'),
                    'menu_icon' => 'dashicons-format-quote',
                    'menu_position' => 25,
                    'hierarchical' => true,
                    'show_in_rest' => true,
                    'show_ui' => true,
                    'show_in_menu' => true,
                    'show_in_admin_bar' => true,
                    'can_export' => true,
                    'has_archive' => false,
                    'publicly_queryable' => true,
                )
            );
        }

    }

}