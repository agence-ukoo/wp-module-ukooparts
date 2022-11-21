<?php

/**
 * Plugin name: Wetreep Custom Reviews
 * Plugin URI: http://www.wetreep.com/
 * Description: Custom Reviews for Wetreep
 * Version: 1.0
 * Requires at least: 3.8
 * Tested up to: 4.0
 * Author: Mohamed Amar FILALI
 * Author URI: http://www.wetreep.com/
 * License: GPL2
 * Text Domain: wetreep-custom-reviews
 * Domain Path: /languages/
 */

/*
MV Slider is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
MV Slider is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with MV Slider. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if (!defined('ABSPATH')) {
    die("This is just a wordpress plugin");
    exit;
}

if (!class_exists('WT_Custom_Reviews')) {
    class WT_Custom_reviews
    {

        function __construct()
        {
            $this->define_constants();

            require_once(WT_REVIEWS_PATH . 'post-types/class.wt-reviews-cpt.php');
            $WT_Reviews_post_type = new WT_Reviews_post_type();

            require_once(WT_REVIEWS_PATH . 'ajax/class.wt-reviews-ajax.php');
            $WT_Reviews_ajax = new WT_Reviews_ajax();

            require_once(WT_REVIEWS_PATH . 'helpers/class.wt_reviews_helpers.php');
            $WT_Reviews_helpers = new WT_Reviews_helpers();

            require_once(WT_REVIEWS_PATH . 'views/wt_single_product_page_extras.php' );
            $WT_Single_Product_Page_Extras = new WT_Single_Product_Page_Extras();

            add_action("wt_reviews_review_page", array($this, 'review_page'));

            add_action('wp_enqueue_scripts', array($this, 'scripts_enqueuer'), 1);

            add_action('admin_enqueue_scripts', array($this, 'admin_scripts_enqueuer'), 1);
        }

        public function review_page()
        {
            wp_enqueue_script('wt-popper');
            wp_enqueue_script('wt-tippy');
            wp_enqueue_script('wt-custon-tippy', WT_REVIEWS_URL . 'vendors/custom-tippy.js', array('wt-tippy'), WT_REVIEWS_VERSION, true);
            wp_localize_script('wt-custon-tippy', 'textContent', array('content' => __("To report this review contact hello@wetreep.com", "golo")));
            wp_enqueue_script('wt-reviews-global-scripts');
            wp_localize_script('wt-reviews-global-scripts', 'textContent', array('content' => __("Review recent trips and people", "golo")));
            require_once(WT_REVIEWS_PATH . 'templates/review-page.php');
        }

        public function admin_scripts_enqueuer()
        {
            wp_register_script('admin-reviews-rating', WT_REVIEWS_URL . 'vendors/review-stars/js/jquery.star-rating-svg.js', array('jquery'), WT_REVIEWS_VERSION, true);
            wp_register_style('admin-rating-style', WT_REVIEWS_URL . 'vendors/review-stars/css/star-rating-svg.css', array(), WT_REVIEWS_VERSION);

            wp_register_script('admin-main', WT_REVIEWS_URL . 'vendors/admin-main.js', array('jquery'), WT_REVIEWS_VERSION, true);
            wp_register_style('admin-style', WT_REVIEWS_URL . 'vendors/admin-style.css', array(), WT_REVIEWS_VERSION);
        }

        public function scripts_enqueuer()
        {
            wp_enqueue_script('jquery-ui-dialog');
            wp_enqueue_style('wp-jquery-ui-dialog');
            wp_enqueue_script('jquery-ui-accordion');
            wp_enqueue_style('wp-jquery-ui-accordion');

            wp_enqueue_script('wt-reviews_custom_scripts', WT_REVIEWS_URL . 'vendors/main.js', array('jquery'),  WT_REVIEWS_VERSION, false);

            wp_register_script('wt-reviews-custon-dialog-script', WT_REVIEWS_URL . 'vendors/custom-dialog.js', array('jquery'), WT_REVIEWS_VERSION, true);
            wp_register_script('wt-reviews-global-scripts', WT_REVIEWS_URL . 'assets/js/scripts.js', array('jquery'), WT_REVIEWS_VERSION, true);

            // Adding script for review star svg
            wp_enqueue_script('wt-reviews-star-rating', WT_REVIEWS_URL . 'vendors/review-stars/js/jquery.star-rating-svg.js', array('jquery'),  WT_REVIEWS_VERSION, false);
            wp_enqueue_style('wt-reivews-star-rating-css', WT_REVIEWS_URL . 'vendors/review-stars/css/star-rating-svg.css', array(),  WT_REVIEWS_VERSION, false);
            wp_enqueue_style('wt_reviews-global_style', WT_REVIEWS_URL . 'assets/css/style.css', array(),  WT_REVIEWS_VERSION, false);

            // Adding script for dialog star reviews
            wp_enqueue_script( 'wt_reviews-rater', WT_REVIEWS_URL . 'vendors/rater/rater.min.js', array( 'jquery' ), WT_REVIEWS_VERSION, true );

            // wizard for the global review dialog
            wp_register_script('wt-reviews-wizard', WT_REVIEWS_URL . 'vendors/jquery-steps/jquery-steps.js', array('jquery'), WT_REVIEWS_VERSION, true);
            wp_register_style('wt-reviews-wizard-css', WT_REVIEWS_URL . 'vendors/jquery-steps/jquery-steps.css', array(), WT_REVIEWS_VERSION);
            wp_register_style('wt-reviews-wizard-theme-css', WT_REVIEWS_URL . 'vendors/jquery-steps/style.css', array(), WT_REVIEWS_VERSION);
            wp_register_script('wt-reviews-wizard-custom-script', WT_REVIEWS_URL . 'vendors/jquery-steps/script.js', array('jquery'), WT_REVIEWS_VERSION, true);
            wp_localize_script('wt-reviews-wizard-custom-script', 'dataObj', array('ajaxurl' => admin_url('admin-ajax.php')));

            // Adding tooltip script
            wp_register_script('wt-popper', 'https://unpkg.com/@popperjs/core@2', array('jquery'),  WT_REVIEWS_VERSION, false);
            wp_register_script('wt-tippy', 'https://unpkg.com/tippy.js@6', array('jquery'),  WT_REVIEWS_VERSION, false);

            // Adding add script ajax call script
            wp_enqueue_script('wt-reviews-ajax', WT_REVIEWS_URL . 'vendors/ajax.js', array('jquery'),  WT_REVIEWS_VERSION, true);

            wp_localize_script('wt-reviews-ajax', 'wt_reviews_ajax_object', array(
                'ajax_url' => admin_url('admin-ajax.php'),
            ));

            wp_enqueue_style('wt_reviews-style', WT_REVIEWS_URL . 'vendors/style.css', array(), WT_REVIEWS_VERSION);
        }


        public function define_constants()
        {
            define('WT_REVIEWS_PATH', plugin_dir_path(__FILE__));
            define('WT_REVIEWS_URL', plugin_dir_url(__FILE__));
            define('WT_REVIEWS_VERSION', '1.0');
            define('WT_REVIEWS_EXTRA_ID', '237');
        }

        public function activate()
        {
            update_option('rewrite_rules', '');
            unregister_post_type('wt-reviews');
        }

        public function deactivate()
        {
            flush_rewrite_rules();
        }

        public function uninstall()
        {
        }
    }
}

if (class_exists('WT_Custom_Reviews')) {
    register_activation_hook(__FILE__, array('WT_Custom_Reviews', 'activate'));
    register_deactivation_hook(__FILE__, array('WT_Custom_Reviews', 'deactivate'));
    register_uninstall_hook(__FILE__, array('WT_Custom_Reviews', 'uninstall'));
    $wt_custom_reviews = new WT_Custom_Reviews();
}
