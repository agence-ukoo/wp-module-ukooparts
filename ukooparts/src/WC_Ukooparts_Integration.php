<?php

namespace Ukoo\Ukooparts;

use WC_Integration;

if (!class_exists('WC_Ukooparts_Integration') ) :
class WC_Ukooparts_Integration extends WC_Integration
{
    /**
     * Init and hook in the integration.
     */
    public function __construct() {
        global $woocommerce;
        $this->id                 = 'my-plugin-integration';
        $this->method_title       = __( 'Paramètres Ukooparts');
        $this->method_description = __( 'Paramètres UkooParts pour WooCommerce');
        // Load the settings.
        $this->init_form_fields();
        $this->init_settings();
        // Define user set variables.
        $this->custom_name          = $this->get_option( 'custom_name' );
        // Actions.
        add_action( 'woocommerce_update_options_integration_' .  $this->id, array( $this, 'process_admin_options' ) );
    }
    /**
     * Initialize integration settings form fields.
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'custom_name' => array(
                'title'             => __( 'Custom Name'),
                'type'              => 'text',
                'description'       => __( 'Enter Custom Name'),
                'desc_tip'          => true,
                'default'           => '',
                'css'      => 'width:170px;',
            ),
            'custom_name_2' => array(
                'title'             => __( 'Custom Name 2'),
                'type'              => 'text',
                'description'       => __( 'Enter Custom Name'),
                'desc_tip'          => true,
                'default'           => '',
                'css'      => 'height:170px;',

            ),
        );
    }
}
endif;