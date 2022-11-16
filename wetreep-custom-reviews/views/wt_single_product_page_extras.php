<?php

class WT_Single_Product_Page_Extras
{


    public function __construct()
    {
        // Adding reviews to each product page
        add_action('woocommerce_after_single_product', array($this, 'display_all_reviews'), 999);

        // Adding all user's reviews to the profile page
        add_action( 'wt_view_user_reviews', array( $this, 'display_user_reviews'), 10, 2 );

        // Adding all user's reviews to profile page
        add_action( 'wt_view_user_reviews_profile', array( $this, 'display_user_reviews_profile' ), 10, 2 );


    }

    public function display_user_reviews_profile( $user_id, $order_product_ids ) {

        $content = '';

        $all_comments = array();

        foreach( $order_product_ids as $order_product_id ){
            $args = array(
                'type' => 'review',
                'post_id' => $order_product_id,
                'status' => 'approve',
                'user_id' => $user_id,
            );

            $comment_query = new WP_Comment_Query;
            $comments = $comment_query->query( $args );

            $all_comments = array_merge( $all_comments, $comments );
        }

        if( $all_comments ){
            $content .= '<div class="block-heading">';
            $content .= '<h3>' . sprintf(__('Comments (%1$s)', 'golo'), count( $all_comments )) . '</h3>';
            $content .= '</div>';
            $content .= '<ul class="wt-reviews-container">';
            foreach( $all_comments as $comment ){
                // Formating the date and time
                $timestamp = strtotime($comment->comment_date); //Changing comment time to timestamp
                $date = date('F d, Y', $timestamp);

                // Getting the commented post title and url
                $post_title = get_the_title( $comment->comment_post_ID );
                $permalink = get_permalink( $comment->comment_post_ID );
                add_image_size( 'wt-review-thumbnail', 100, 100, 
                array( 
                    'x_crop_position' => 'center', 
                    'y_crop_position' => 'center' 
                    ) 
                );
                $post_thumbnail = get_the_post_thumbnail( $comment->comment_post_ID, 'wt-review-thumbnail' );

                // Getting the avatar of the user
                $avatar_url = get_avatar_url( $comment->user_id );
                $author_avatar_image_url = get_the_author_meta( 'author_avatar_image_url', $comment->user_id);
                if( ! empty( $author_avatar_image_url ) ){
                    $avatar_url = $author_avatar_image_url;
                }

                if (filter_var($author_avatar_image_url, FILTER_VALIDATE_URL)) {
                    $avatar_url_obj = golo_image_resize_url($avatar_url, 50, 50, true);
                    $avatar_url = $avatar_url_obj['url'];
                } else {
                    $avatar_url = wp_get_attachment_url($author_avatar_image_url);
                }

                $content .= '<li itemprop="reviews" itemscope itemtype="http://schema.org/Review"  id="li-review-' . $comment->comment_ID . ' " class="profile-trip-review" data-rating="' . esc_attr( get_comment_meta( $comment->comment_ID, 'rating', true ) ) . '" >';
                    $content .= '<div id="review-' . $comment->comment_ID .'" class="review-container">';
                    $content .= '<a href="' . esc_url( $permalink )  . '" target="_blank" >';
                        $content .= '<div class="review-avatar-profile">';
                            $content .= $post_thumbnail;
                        $content .= '</div>';
                    $content .= '</a>';
                        $content .= '<div class="review-content">';
                            $content .= '<div class="author-details">';
                                $content .= '<div class="review-author">';
                                        $content .= '<div class="star-rating-container">';
                                            $content .= '<em class="review-date">';
                                                $content .= '<time itemprop="itemPublished" datetime="' . $comment->comment_date . '" />' . $date . '</time>';
                                            $content .= '</em>';
                                        $content .= '</div>';
                                    $content .= '</div>';
                                $content .= '<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="profile-author-rating" title="' . esc_attr( get_comment_meta( $comment->comment_id, 'rating', true  ) )  . ' ">';
                                $content .= '</div>';
                            $content .= '</div>';
                        $content .= '<div class="clear"></div>';
                        $content .= '<div class="review-text">';
                        $content .= '<div itemprops="description" class="description">' . $comment->comment_content . '</div>' ;
                        $content .= '</div>';
                        $content .= '<div class="clear"></div>';
                        $content .= '</div>';
                    $content .= '</div>';
                $content .= '</li>';
            }

            $content .= '</ul>';

        } else {
            $content .= '<p>' . __('No reviews found.', 'wetreep-custom-reviews') . '</p>';
        }
        
        echo $content;
    }

    public function display_user_reviews( $user_id, $order_product_ids ) {

        $content = '';

        $all_comments = array();

        foreach( $order_product_ids as $order_product_id ){
            $args = array(
                'type' => 'review',
                'post_id' => $order_product_id,
                'status' => 'approve',
                'user_id' => $user_id,
            );

            $comment_query = new WP_Comment_Query;
            $comments = $comment_query->query( $args );

            $all_comments = array_merge( $all_comments, $comments );
        }

        if( $all_comments ){
            $content .= '<ul class="wt-reviews-container custom-scrollbar">';
            foreach( $all_comments as $comment ){
                // Formating the date and time
                $timestamp = strtotime($comment->comment_date); //Changing comment time to timestamp
                $date = date('F d, Y', $timestamp);

                // Getting the commented post title and url
                $post_title = get_the_title( $comment->comment_post_ID );
                $permalink = get_permalink( $comment->comment_post_ID );

                // Getting the avatar of the user
                $avatar_url = get_avatar_url( $comment->user_id );
                $author_avatar_image_url = get_the_author_meta( 'author_avatar_image_url', $comment->user_id);
                if( ! empty( $author_avatar_image_url ) ){
                    $avatar_url = $author_avatar_image_url;
                }

                if (filter_var($author_avatar_image_url, FILTER_VALIDATE_URL)) {
                    $avatar_url_obj = golo_image_resize_url($avatar_url, 50, 50, true);
                    $avatar_url = $avatar_url_obj['url'];
                } else {
                    $avatar_url = wp_get_attachment_url($author_avatar_image_url);
                }


                $content .= '<li itemprop="reviews" itemscope itemtype="http://schema.org/Review"  id="li-review-' . $comment->comment_ID . ' " class="profile-trip-review" data-rating="' . esc_attr( get_comment_meta( $comment->comment_ID, 'rating', true ) ) . '" >';
                    $content .= '<div id="review-' . $comment->comment_ID .'" class="review-container">';
                        $content .= '<div class="review-avatar">';
                            $content .= '<img src="' . esc_url($avatar_url) . '" alt="' . esc_attr($comment->comment_author) . '" />';
                        $content .= '</div>';
                        $content .= '<div class="review-content">';
                            $content .= '<div class="author-details">';
                                $content .= '<div class="review-author">';
                                    $content .= '<h3 class="review-author-name">' . $comment->comment_author . '</h3>';
                                        $content .= '<div class="star-rating-container">';
                                            $content .= '<em class="review-date">';
                                                $content .= '<time itemprop="itemPublished" datetime="' . $comment->comment_date . '" />' . $date . '</time>';
                                            $content .= '</em>';
                                        $content .= '</div>';
                                    $content .= '</div>';
                                $content .= '<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="profile-author-rating" title="' . esc_attr( get_comment_meta( $comment->comment_id, 'rating', true  ) )  . ' ">';
                                $content .= '</div>';
                            $content .= '</div>';
                        $content .= '<div class="clear"></div>';
                        $content .= '<div class="review-text">';
                        $content .= '<div itemprops="description" class="description">' . $comment->comment_content . '</div>' ;
                        $content .= '</div>';
                        $content .= '<div class="clear"></div>';
                        $content .= '<div class="comment-for" >';
                        $content .=  __('Comment for: ', 'wetreep-custom-reviews') . '<a href="' . esc_url($permalink) . '" target="_blank" >' . $post_title . '</a>';
                        $content .= '</div>';
                        $content .= '</div>';
                    $content .= '</div>';
                $content .= '</li>';
            }

            $content .= '</ul>';

        } else {
            $content .= '<p>' . __('No reviews found.', 'wetreep-custom-reviews') . '</p>';
        }
        
        echo $content;
    }

    //Display all product reviews
    public function display_all_reviews()
    {
        global $product;
        $id = $product->id;

        $args = array(
            'post_id' => $id,
            'status' => 'approve',
            'type' => 'review'
        );

        // The Query
        $comments_query = new WP_Comment_Query;
        $comments = $comments_query->query($args);

        // Comment Loop
        if ($comments) {
            echo '<div class="reviews-wrapper single-product-page" >';
            echo '<h2>' . __('Reviews', 'wetreep-custom-reviews') . '</h2>';
            echo "<ul class='wt-reviews-container custom-scrollbar'>";
            foreach ($comments as $comment) : ?>
                <?php
                    $timestamp = strtotime($comment->comment_date); //Changing comment time to timestamp
                    $date = date('F d, Y', $timestamp);
                ?>
                <?php if ($comment->comment_approved == '0') : ?>
                    <p class="meta waiting-approval-info">
                        <em><?php _e('Thanks, your review is awaiting approval', 'woocommerce'); ?></em>
                    </p>
                <?php endif;  ?>
                <li itemprop="reviews" itemscope itemtype="http://schema.org/Review" <?php comment_class( 'trip-review-item' ); ?> id="li-review-<?php echo $comment->comment_ID; ?>" data-rating="<?php echo esc_attr( get_comment_meta( $comment->comment_ID, 'rating', true ) ); ?>" >
                    <div id="review-<?php echo $comment->comment_ID; ?>" class="trip-review-container">
                        <div class="review-header">
                            <div class="review-avatar">
                                <?php echo get_avatar($comment->comment_author_email, $size = '50'); ?>
                            </div>
                            <div class="review-summary-wrapper" >
                                <div class="review-name-rate">
                                    <div class="review-author-name" itemprop="author"><?php echo $comment->comment_author; ?></div>
                                    <div class="trip-review-rate"></div>
                                </div>
                                <div class="review-date-wrapper" >
                                    <em class="review-date">
                                        <time itemprop="datePublished" datetime="<?php echo $comment->comment_date; ?>"><?php echo $date; ?></time>
                                    </em>
                                </div>
                            </div>
                        </div>
                        <div class="review-content">
                            <div class="review-text">
                                <div itemprop="description" class="description">
                                    <?php echo $comment->comment_content; ?>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </li>

        <?php
        endforeach;
        echo "</ul>";
        echo '</div>';

        echo '<script>
        (function($) {
            $(document).ready(function() {
                var singleProductReviews = $(".reviews-wrapper.single-product-page");
                var yelpReviews = $(".place-review-yelp");
                $(singleProductReviews).insertAfter(yelpReviews);            
            });
        })(jQuery);
        
        </script>
        ';

        } else {
            echo "This product hasn't been rated yet.";
        }
    }
}
