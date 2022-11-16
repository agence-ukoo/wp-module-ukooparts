
<div class="participants-list" id="participantGroup">
    <?php foreach( $participants_array as $user_participant ){ ?>
        <?php
            if( intval($user_participant['participant_id']) != get_current_user_id() ){
                $received_reviews = get_post_meta( $user_participant['product_id'], 'received_reviews', true ) ? get_post_meta( $user_participant['product_id'], 'received_reviews', true ) : [];
                $this_review = array(
                    $user_participant['product_id'] . $user_participant['participant_id'] => array(
                        'user_from' => get_current_user_id(),
                        'user_to' => $user_participant['participant_id'],
                    )
                );
                
                $already_reviewed = $this->already_reviewed_participant( $this_review, $received_reviews );
        ?>
        <h6 class="participant-item">
            <div class="participant-item-avatar">
                <img src="<?php echo esc_attr($user_participant['participant_avatar']) ?>" alt="<?php echo esc_attr($user_participant['participant_name']) ?>">
            </div>
            <div class="participant-item-name"><h6><?php echo esc_html($user_participant['participant_name']) ?></h6></div>
        </h6>
        <div class='participant-form-item'>
            <p><?php echo __( 'Review for ', 'wetreep-custom-reviews' ) . ' ' . $user_participant['participant_name'] ?></p>
            <?php if( ! $already_reviewed ) : ?>
            <div class="participant-form-item-content" >
                <textarea name="participant_review" class="cm-textarea textarea-<?php echo $participant ?>" id="participant_review_textarea" rows="10"></textarea>
                <div class="review-form-error"></div>
                <div class="participant-form-item-rating">
                    <p><?php echo __('Your rating', 'wetreep-custom-reviews') ?></p>
                    <div class="global-review-rating-participant"></div>
                </div>
                <input type="hidden" name="participant_rating" id="participant_rating" />
                <input type="hidden" name="pariticpant_id" id="participant_id" value="<?php echo esc_attr( $user_participant['participant_id'] ); ?>" >
                <input type="hidden" name="product_id" id="product_id" value="<?php echo  $user_participant['product_id']; ?>" >
                <button class="btn btn-primary btn-frontend gl-button submit-participant-review"><?php echo __('Submit participant review', 'wetreep-custom-reviews') ?></button>
            </div>
            <?php else: ?>
                <div class="participant-form-item">
                    <p><?php echo __("You have already reviewed this participant", 'wetreep-custom-reviews'); ?></p>
                </div>
            <?php endif; ?>
        </div>
        <?php } ?>
    <?php } ?>
</div>

<script>
(function($){
    $(document).ready(function(){
        $(".participant-form-item").each(function(){
            var thisParent = $(this);
            $('.submit-participant-review', this).bind('click', { formContainer: this } , function(e){
                var formContainer = e.data.formContainer;
                var textarea = $(formContainer).find(".cm-textarea");
                var rating = $(formContainer).find("#participant_rating");
                var participant_id = $(formContainer).find("#participant_id");
                var product_id = $(formContainer).find("#product_id");
                console.log("This is the rating: " + rating.val());
                $.ajax({
                    type: "POST",
                    url: <?php echo "'" . admin_url( 'admin-ajax.php' ) . "'";  ?>,
                    data:{
                        action: "wt_reviews_global_post_review",
                        review: textarea.val(),
                        rating: rating.val(),
                        user_to: participant_id.val(),
                        product_id: product_id.val()
                    },
                    success: function(res){
                        var data = JSON.parse(res);
                        if( data.success ){
                            $(formContainer).find(".participant-form-item-content").empty();
                            $(formContainer).find(".participant-form-item-content").html(data.message);
                        } else if( !data.success) {
                            $(formContainer).find(".review-form-error").html(data.message);
                        }
                        
                    }
                })
            });
            // $(this).find(".global-review-rating-participant").starRating({
            //     starSize: 30,
            //     strokeColor: "#894A00",
            //     strokeWidth: 10,
            //     disableAfterRate: false,
            //     starShape: "rounded",
            //     callback: function( currentRating, el ){
            //         thisParent.find("#participant_rating").val(currentRating);
            //     }
            // });
            $(this).find(".global-review-rating-participant").rate({
                cursor: 'pointer',
                symbols: {
                    utf8_hexagon: {
                        base: '<img src="../../../../../../wp-content/plugins/wetreep-custom-reviews/vendors/rater/star_empty.png" width="24" height="24"/>',
                        hover: '<img src="../../../../../../wp-content/plugins/wetreep-custom-reviews/vendors/rater/star_full.png" width="24" height="24"/>',
                        selected: '<img src="../../../../../../wp-content/plugins/wetreep-custom-reviews/vendors/rater/star_full.png" width="24" height="24"/>',
                    },
                },
                selected_symbol_type: 'utf8_hexagon',
                convert_to_utf8: false,
            });
            $(this).find(".global-review-rating-participant").on("change", function(){
                thisParent.find("#participant_rating").val($(this).rate('getValue'));
            });
        });
        $("#participantGroup").accordion({
            heightStyle: "content",
            header: "h6",
        });
    });
})(jQuery);
</script>