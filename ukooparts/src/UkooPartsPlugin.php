<?php

namespace Ukoo\Ukooparts;

if (!class_exists('UkooPartsPlugin')) :
class UkooPartsPlugin
{
    const TRANSIENT_UKOOPARTS_PLUGIN_ACTIVATED = 'ukooparts_plugin_activated';

    public function __construct(string  $file){
        add_action( 'plugins_loaded', array( $this, 'init' ) );

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
}

endif;

