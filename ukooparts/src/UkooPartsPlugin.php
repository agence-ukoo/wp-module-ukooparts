<?php

namespace Ukoo\Ukooparts;

if (!class_exists('UkooPartsPlugin')) :
class UkooPartsPlugin
{
    const TRANSIENT_UKOOPARTS_PLUGIN_ACTIVATED = 'ukooparts_plugin_activated';

    public function __construct(string  $file){
        add_action( 'plugins_loaded', array( $this, 'init' ) );
        add_action( 'init', array( $this, 'create_ukooparts_post_type' ) );
        add_action( 'init', array( $this, 'add_page_to_init' ) );

        register_activation_hook($file, [$this, 'PluginActivation']);
        add_action('admin_notices', [$this, 'NoticeActivation']);

    }

    public function init(): void {
        // Checks if WooCommerce is installed.
        if ( class_exists( 'WC_Integration' ) ) {

            // Include our integration class.
            include_once 'WC_Ukooparts_Integration.php';
            // Register the integration.
            add_filter( 'woocommerce_integrations', array( $this, 'add_integration' ) );

            // Set the plugin slug
            define( 'UKOOPARTS_SLUG', 'wc-settings' );

            // Setting action for plugin
            add_filter( 'plugin_action_links', array( $this, 'ukooparts_action_links' ) );
        }
    }

    /**
     * Add a new integration to WooCommerce.
     */
    public function add_integration( $integrations ) {
        $integrations[] = 'Ukoo\Ukooparts\WC_Ukooparts_Integration';
        return $integrations;
    }

    public function ukooparts_action_links( $links ) {

        $links[] = '<a href="'.menu_page_url( UKOOPARTS_SLUG, false ) .'&tab=integration">Settings</a>';
        return $links;
    }
/*
 * GESTION DES NOTIFICATIONS
 * */
    public function PluginActivation(): void{
        set_transient(self::TRANSIENT_UKOOPARTS_PLUGIN_ACTIVATED, true);
    }

    public function NoticeActivation(): void{
        if (get_transient(self::TRANSIENT_UKOOPARTS_PLUGIN_ACTIVATED)){
            self::render('notice', [
                'text_color' => "#011627",
                'bg_color' => "#e2eafc",
                'height' => 6,
                'message' => "<strong>Bienvenue et merci d'avoir installé Ukooparts !</strong>"
            ]);
            delete_transient(self::TRANSIENT_UKOOPARTS_PLUGIN_ACTIVATED);
        }
    }

    public static function render(string $name, array $args = []): void{
        extract($args);
        $file = UKOOPARTS_PLUGIN_DIR. "views/$name.php";
        ob_start();
        include_once($file);

        echo  ob_get_clean();
    }

    public function create_ukooparts_post_type(): void{
        // Register Custom Post Type
            $labels = array(
                'name' => _x('UkooParts', 'UkooParts'),
                'singular_name' => _x('UkooParts', 'UkooParts'),
                'menu_name' => __('UkooParts'),
                'name_admin_bar' => __('UkooParts'),
                'archives' => __('Item Archives'),
                'attributes' => __('Item Attributes'),
                'parent_item_colon' => __('Parent Item:'),
                'all_items' => __('All Items'),
                'add_new_item' => __('Add New Item'),
                'add_new' => __('Add New', 'ukooparts'),
                'new_item' => __('New Item'),
                'edit_item' => __('Edit Item'),
                'update_item' => __('Update Item'),
                'view_item' => __('View Item'),
                'view_items' => __('View Items'),
                'search_items' => __('Search Item'),
                'not_found' => __('Not found'),
                'not_found_in_trash' => __('Not found in Trash'),
                'featured_image' => __('Featured Image'),
                'set_featured_image' => __('Set featured image'),
                'remove_featured_image' => __('Remove featured image'),
                'use_featured_image' => __('Use as featured image'),
                'insert_into_item' => __('Insert into item'),
                'uploaded_to_this_item' => __('Uploaded to this item'),
                'items_list' => __('Items list'),
                'items_list_navigation' => __('Items list navigation'),
                'filter_items_list' => __('Filter items list'),
            );
            $args = array(
                'label' => __('UkooParts', 'text_domain'),
                'description' => __('UkooParts section', 'text_domain'),
                'labels' => $labels,
                'supports' => false,
                'taxonomies' => array('category', 'post_tag'),
                'hierarchical' => false,
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'menu_position' => 5,
                'show_in_admin_bar' => true,
                'show_in_nav_menus' => true,
                'can_export' => true,
                'has_archive' => true,
                'exclude_from_search' => false,
                'publicly_queryable' => true,
                'capability_type' => 'page',
                'menu_icon' => 'dashicons-welcome-view-site'
            );
            register_post_type('ukooparts', $args);
        }

        function add_page_to_init(): void
        {
            foreach($this->get_ukooparts_post() as $array_entry) {

                $array = extract($array_entry);
                extract((array)$array);
                        if (!get_page_by_path($url, OBJECT, 'ukooparts'))
                            wp_insert_post(array(
                                'post_type' => "ukooparts",
                                'post_content' => $content,
                                'post_title' => $title,
                                'post_name' => $url, //this is for the URL
                                'post_status' => "publish",
                                'comment_status' => "closed",
                                'ping_status' => "closed",
                                'post_parent' => "0",
                            ));
                    }
        }

    function create_ukooparts_post(string $post_url, string $post_title, string $post_content): array
    {
        return array(
            'url' => $post_url,
            'title' => $post_title,
            'content' => $post_content
        );
    }

    function get_ukooparts_post():array{
        return array(
            $this->create_ukooparts_post('cadeaux','Liste des cadeaux !', '[cadeaux]'),
            $this->create_ukooparts_post('manufacturers','Liste des constructeurs', '[manufacturers]'),
            $this->create_ukooparts_post('models','Liste des modèles', '[models]'),
            $this->create_ukooparts_post('fiche-descriptif','fiche descriptif', '[descriptif]'),
            $this->create_ukooparts_post('topmoto','Top 50 bécanes', '[topmoto]'),
        );
    }
    

}
endif;