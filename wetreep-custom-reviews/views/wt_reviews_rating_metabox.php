<?php
    $rating = get_post_meta( $post->ID, 'rating', true );
?>

<table class="form-table wt-reviews-rating-metabox">
    <input type="hidden" value="<?php echo wp_create_nonce( 'wt_reviews_rating_nonce_action' ); ?>">
    <tr>
        <th class="rating-label-container" >
            <label for="wt_reviews_rating"><?php esc_html_e( 'rating', 'wetreep-custom-reviews' ) ?></label>
        </th>
        <td>
            <div class="admin-review-rating"></div>
            <input type="hidden" name="wt_rating" id="admin-rating-stars" value="<?php echo $rating; ?>">
        </td>
    </tr>
</table>