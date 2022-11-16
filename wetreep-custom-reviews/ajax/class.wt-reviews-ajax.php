<?php

if (!class_exists('WT_Reviews_Ajax')) {
    class WT_Reviews_Ajax
    {

        public function __construct()
        {

            add_action('wp_ajax_wt_reviews_post_review', array($this, 'post_review'));
            add_action('wp_ajax_nopriv_wt_reviews_post_review', array($this, 'post_review'));

            add_action('wp_ajax_wt_reviews_post_review_answer', array($this, 'post_review_answer'));
            add_action('wp_ajax_nopriv_wt_reviews_post_review_answer', array($this, 'post_review_answer'));

            add_action('wt-submit-review-button', array($this, 'submit_review_button'));

            // Loading all participatns by ajax and displaying them in the review dialog
            add_action('wp_ajax_wt_reviews_get_participants', array($this, 'ajax_get_participants'));
            add_action('wp_ajax_nopriv_wt_reviews_get_participants', array($this, 'ajax_get_participants'));

            // Add global trip review from with ajax
            add_action( 'wp_ajax_wt_reviews_global_trip_review', array( $this, 'ajax_global_trip_review' ) );
            add_action( 'wp_ajax_nopriv_wt_reviews_global_trip_review', array( $this, 'ajax_global_trip_review' ) );

            // Ajax function to submit participant review 
            add_action( 'wp_ajax_wt_reviews_global_post_review', array( $this, 'global_post_review' ) );
            add_action( 'wp_ajax_nopriv_wt_reviews_global_post_review', array( $this, 'global_post_review' ) );
        }

        // Ajax function to submit participant review
        public function global_post_review(){
            $review = $_POST['review'];
            $rating = $_POST['rating'];
            $user_from = get_current_user_id();
            $user_to = $_POST['user_to'];
            $product_id = $_POST['product_id'];
            $received_reviews = get_post_meta( $product_id, 'received_reviews', true ) ? get_post_meta( $product_id, 'received_reviews', true ) : array();
            $review_proof = array(
                    'user_from' => $user_from, 
                    'user_to' => $user_to,
            );

            // echo print_r($received_reviews, true);
            // die();

            // echo print_r( $received_reviews );

            // echo print_r( $review_proof );

            // update_post_meta( $product_id, 'received_reviews', array() );
            // die();

            $user_to_obj = get_user_by( 'id', $user_to );
            $user_from_object = get_user_by( 'id', $user_from );

            $user_from_login = $user_from_object->user_login;
            $user_to_login = $user_to_obj->user_login;

            if( empty( $review ) || empty( $rating ) ){
                $response = array(
                    'success' => false,
                    'message' => __( 'Please fill in the review and rate the participant', 'wetreep-custom-reviews' ),
                );
                echo json_encode( $response );
                die();
            } else if( $this->already_reviewed_participant( $review_proof, $received_reviews ) ){
                $response = array( 
                    'success' => false,
                    'message' => __( 'You have already submitted a review for this user', 'wetreep-custom-reviews' )
                );
                echo json_encode( $response );
                die();
            } else {
                $review_args = array(
                    'post_title'    => 'Review: ' . $user_from_login . ' to ' . $user_to_login,
                    'post_content'  => $review,
                    'post_status'   => 'publish',
                    'post_type'     => 'wt_reviews',
                    'post_author'   => get_current_user_id(),
                    'meta_input'    => array(
                        'rating' => $rating,
                        'from'   => get_current_user_id(),
                        'to'     => $user_to
                    )
                );

                wp_insert_post( $review_args );

                $received_reviews[$product_id . $user_to] = $review_proof;

                update_post_meta( $product_id, 'received_reviews', $received_reviews );

                $response = array(
                    'success' => true,
                    'message' => __( 'Review submitted successfully', 'wetreep-custom-reviews' ),
                    'received_reviews' => $received_reviews
                );

            }

            echo json_encode($response);

            die();

        }

        // Function that checks if the user has already submitted a review for this participant
        public function already_reviewed_participant( $needle, $array ) {
            $default_value = false;
            foreach( $array as $key => $value ) {
                if( $value == $needle[$key] ) {
                    $default_value = true;
                    break;
                } else {
                    $default_value = false;
                }
            }
            return $default_value;
        }

        // Ajax function to submit trip review
        public function ajax_global_trip_review(){
            $post_id = $_POST['post_id'];
            $review_text = $_POST['review_text'];
            $rating = $_POST['rating'];
            $user_id = get_current_user_id();

            $content = '';
            $button = '';

            $content .= '<div class="already-reviewed" >';
            $content .= '<p>' . __("You've already reviewed this trip", 'wetreep-custom-reviews') . '</p>';
            $content .= '</div>';

            $button .= '<button class="btn btn-primary btn-frontend gl-button reviews-next-without-submit " data-trip-id="' . $post_id . '">' . __('Review participants', 'wetreep-custom-reviews') . '</button>';
            $button .= '<script>';
            $button .= '(function($) {';
            $button .= 'jQuery(document).ready(function($) {';
            $button .= '$(".reviews-next-without-submit", this).on("click", function (e) {
                steps_api.setStepIndex(2);
                steps_api.next();
                $.ajax({
                  url: ' . "'" . admin_url('admin-ajax.php') . "'" . ',
                  type: "POST",
                  data: {
                    action: "wt_reviews_get_participants",
                    trip_id: ' . $post_id . ',
                  },
                  beforeSend: function () {
                    $(".step-tab-panel .loading-rec").show();
                  },
                  success: function (data) {
                    $(".step-tab-panel .loading-rec").hide();
                    var res = JSON.parse(data);
                    $(".participants-list").html(res.content);
                  },
                });
              });';
            $button .= '});';
            $button .= '})(jQuery);';
            $button .= '</script>';

            if( empty( $review_text ) || empty( $rating ) ){
                // Checking if the review text is empty
                $response = array(
                    'success' => false,
                    'message' => __('Please enter a review', 'wetreep-custom-reviews'),
                    'rating' => $rating,
                );
            } else {
                // add comment to a product 
                $comment_id = $this->add_comment( $post_id, $review_text, $rating, $user_id );

                if ($comment_id) {
                    $response = array(
                        'success' => true,
                        'review_id' => $comment_id,
                        'content' => $content,
                        'button' => $button,
                    );
                } else {
                    $response = array(
                        'success' => false,
                        'message' => 'Something went wrong while submitting the review'
                    );
                }
            }

            echo json_encode($response);
            wp_die();
        }

        public function add_comment( $post_id, $review_text, $review_rating, $user_id )
        {
            $comment_id = wp_insert_comment( array(
                'comment_post_ID' => $post_id,
                'comment_author' => get_the_author_meta( 'display_name', $user_id ),
                'comment_author_email' => get_the_author_meta( 'user_email', $user_id ),
                'comment_content' => $review_text,
                'comment_type' => 'review',
                'comment_approved' => 1,
                'user_id' => $user_id
            ) );

            $review_users = get_post_meta( $post_id , '_wt_review_users', true );

            if( empty( $review_users) ){
                $review_users = array();
            }

            // TODO: remove this line in production
            if( ! in_array( $user_id, $review_users ) ){
                update_post_meta( $post_id, '_wt_review_users', array_merge( $review_users, array( $user_id ) ) );
            }

            update_comment_meta( $comment_id, 'rating', $review_rating );

            return $comment_id;
        }

        public function ajax_get_participants(){            
            $trip_id = $_POST['trip_id'];
            $participants = $this->get_all_participants($trip_id);
            $participants_array = array();

            foreach( $participants as $participant ){
                $user = get_user_by('id', $participant);
                $attachement_id = get_user_meta( $participant, 'author_avatar_image_id', true ); 
                $avatar_url = wp_get_attachment_url( $attachement_id, 'thumbnail' );

                $participants_array[] = array(
                    'participant_id' => $participant,
                    'participant_name' => $user->display_name,
                    'participant_avatar' => $avatar_url,
                    'product_id' => $trip_id
                );
            } 


            ob_start();
            require( WT_REVIEWS_PATH . 'views/wt-participants_review.php' );
            $content = ob_get_clean();


            $response = array(
                'success' => true,
                'message' => '',
                'data' => $participants_array,
                'content' => $content
            );

            echo json_encode($response);

            die();
        }

        public function post_review_answer()
        {
            if (isset($_POST['wt_reviews_answer_nonce_field']) && wp_verify_nonce($_POST['wt_reviews_answer_nonce_field'], 'wt_reviews_answer_nonce_action')) {
                $answer = $_POST['wt_user_review_answer'];
                $review_id = $_POST['wt_reviews_answer_review_id'];
                $user_from = get_current_user_id();
                $user_to = $_POST['wt_reviews_answer_from'];

                $user_from_obj = get_user_by('id', $user_from);
                $user_to_obj = get_user_by('id', $user_to);
                $user_from_login = $user_from_obj->user_login;

                $review_args = array(
                    'post_title' => 'Review Answer: ' . $user_from_login,
                    'post_content' => $answer,
                    'post_status' => 'publish',
                    'post_type' => 'wt_reviews',
                    'post_author' => get_current_user_id(),
                    'post_parent' => $review_id,
                    'meta_input'  => array(
                        'to' => $user_to,
                        'from' => $user_from,
                    )
                );

                if (!empty($answer)) {
                    $review_answer_id = wp_insert_post($review_args);
                    if ($review_answer_id) {
                        $response = array(
                            'success' => true,
                            'message' => 'Review answer posted successfully',
                        );
                    } else {
                        $response = array(
                            'success' => false,
                            'message' => 'Review answer could not be posted',
                        );
                    }
                }

                echo json_encode($response);
                exit;
            }
        }

        public function post_review(){
            if (isset($_POST['wt_reviews_nonce_field']) && wp_verify_nonce($_POST['wt_reviews_nonce_field'], 'wt_reviews_nonce_action')) {
                $rating = $_POST['wt_rating'];
                $review = $_POST['wt_user_review'];
                $user_from = get_current_user_id();
                $user_to = $_POST['wt_reviews_from'];
                $review_ids = array();
                $all_participants = array();

                $user_from_obj = get_user_by('id', $user_from);
                $user_to_obj = get_user_by('id', $user_to);
                $user_from_login = $user_from_obj->user_login;
                $user_to_login = $user_to_obj->user_login;

                $ordered_products = $this->get_all_products($user_to);

                foreach ($ordered_products as $prodcut_id) {
                    $participants_ids = array();
                    $participants = $this->get_all_participants($prodcut_id);

                    foreach ($participants as $participant) {
                        $participants_ids[] = $participant;
                    }

                    $all_participants = array_merge($all_participants, $participants_ids);
                }

                $latest_product = $ordered_products[count($ordered_products) - 1];

                $start_date = get_post_meta($latest_product, 'start_date', true);
                $end_date = get_post_meta($latest_product, 'end_date', true);

                $unix_day = strtotime($end_date);
                $unix_day_before = $this->wt_get_day_before($end_date);
                $the_today = new DateTime();
                $today = $the_today->format('U');


                $posted_reviews_args = array(
                    'post_type' => 'wt_reviews',
                    'posts_per_page' => -1,
                    'meta_query' => array(
                        array(
                            'key' => 'from',
                            'value' => $user_to,
                            'compare' => '='
                        ),
                        array(
                            'key' => 'to',
                            'value' => get_current_user_id(),
                            'comare' => '='
                        )
                    )
                );

                $all_reviews = new WP_Query($posted_reviews_args);


                while ($all_reviews->have_posts()) : $all_reviews->the_post();

                    $review_ids[] = get_the_author_ID();

                endwhile;
                wp_reset_query();

                $review_args = array(
                    'post_title'    => 'Review: ' . $user_from_login . ' to ' . $user_to_login,
                    'post_content'  => $review,
                    'post_status'   => 'publish',
                    'post_type'     => 'wt_reviews',
                    'post_author'   => get_current_user_id(),
                    'meta_input'    => array(
                        'rating' => $rating,
                        'from'   => get_current_user_id(),
                        'to'     => $user_to
                    )
                );

                if (!in_array(get_current_user_id(), $review_ids) && in_array(get_current_user_id(), $all_participants)) {
                    $review_id = wp_insert_post($review_args);
                    if ($review_id) {
                        $response = array(
                            'success' => true,
                            'message' => __('Review posted successfully', 'wetreep-custom-reviews')
                        );
                    } else {
                        $response = array(
                            'success' => false,
                            'message' => __('Error posting review', 'wetreep-custom-reviews')
                        );
                    }
                } else {
                    $response = array(
                        'success' => false,
                        'message' => __('You have already posted a review', 'wetreep-custom-reviews')
                    );

                    if (!in_array(get_current_user_id(), $all_participants)) {
                        $response = array(
                            'success' => false,
                            'message' => __('You need to travel with user in order to review him', 'wetreep-custom-reviews')
                        );
                    }
                }


                echo json_encode($response);
                exit;
            }
        }

        public function get_all_participants($product_id)
        {
            global $wpdb;

            $customer_emails = $wpdb->get_col("
            SELECT DISTINCT pm.meta_value FROM {$wpdb->posts} AS p
            INNER JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id
            INNER JOIN {$wpdb->prefix}woocommerce_order_items AS i ON p.ID = i.order_id
            INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS im ON i.order_item_id = im.order_item_id
            WHERE p.post_status IN ( 'wc-completed' )
            AND pm.meta_key IN ( '_customer_user' )
            AND im.meta_key IN ( '_product_id', '_variation_id' )
            AND im.meta_value = $product_id
            ");

            return $customer_emails;
        }

        private function get_all_products($user_id)
        {
            $products_array = array();

            $args = array(
                'customder_id' => $user_id
            );

            $orders = wc_get_orders($args);

            foreach ($orders as $order_item) {

                $order = wc_get_order($order_item);
                $items = $order->get_items();
                foreach ($items as $item) {
                    $product_id = $item['product_id'];
                    $product = wc_get_product($product_id);

                    $category_ids = $product->get_category_ids();

                    if (!in_array(WT_REVIEWS_EXTRA_ID, $category_ids)) {
                        $products_array[] = $product_id;
                    }
                }
            }

            return array_unique($products_array);
        }

        public function wt_format_date_to_unix($date_string)
        {
            $year = substr($date_string, 0, 4);
            $month = substr($date_string, 4, 2);
            $day = substr($date_string, 6, 2);

            $date = new DateTime();
            date_date_set($date, $year, $month, $day);

            return date_timestamp_get($date);
        }

        public function wt_get_day_before($date_unix)
        {
            $newDate = strtotime($date_unix) - 60 * 60 * 24;
            return $newDate;
        }

        public function wt_get_week_after($date_unix)
        {
            $newDate = strtotime($date_unix) + 60 * 60 * 24 * 7;
            return $newDate;
        }

        public function submit_review_button($user_id)
        {
            $ordered_products = $this->get_all_products($user_id);
            $latest_product = $ordered_products[count($ordered_products) - 1];
            $all_participants = array();

            $user = get_user_by('id', $user_id);

            $user_login = $user->display_name;

            $dialog_title = sprintf(__('Votre expérience avec %1$s', 'wetreep-custom-reviews'), $user_login);

            wp_enqueue_script('wt-reviews-custon-dialog-script');
            wp_localize_script('wt-reviews-custon-dialog-script', 'dialogBox', array('user' => $dialog_title));

            foreach ($ordered_products as $product) {
                $participants = $this->get_all_participants($product);

                $all_participants = array_merge($all_participants, $participants);
            }


            $start_date = get_post_meta($latest_product, 'start_date', true);
            $end_date = get_post_meta($latest_product, 'end_date', true);

            // testing purpose
            $new_today = strtotime('20220705');

            $unix_day = strtotime($end_date);
            $unix_day_before = $this->wt_get_day_before($end_date);
            $unix_week_after = $this->wt_get_week_after($end_date);
            $the_today = new DateTime();
            $today = $the_today->format('U');

            if ($today <= $unix_day_before && $new_today <= $unix_week_after  && in_array(get_current_user_id(), $all_participants) && get_current_user_id() != $user_id) {
                $content =  "";
                $content .= '<a href="' . get_site_url() . '/mon-compte/my-reviews">';
                $content .= '<button class="gl-button"><i class="la la-envelope icon-large"></i>' . esc_html__('Leave a review', 'golo-framework') . '</button>';
                $content .= '</a>';
            }

            echo $content;
        }
    }
}
