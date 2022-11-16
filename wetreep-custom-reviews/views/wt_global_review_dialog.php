<?php

    $args = [
        'customer_id' => get_current_user_id(),
        'status' => ['completed']
    ];

    $orders = wc_get_orders( $args );

    $trips = [];
    foreach( $orders as $order ){
        $order_id = $order->get_id();
        $order_items = $order->get_items();
        foreach( $order_items as $order_item ){
            $product_id = $order_item->get_product_id();
            if( !in_array( $trip_id, $trips ) ){
                $product = wc_get_product( $product_id );
				$id = $product->get_id();
				$end = get_field('end_date', $id);
				$datetest = str_replace('/', '-', $end);
				$endd = strtotime($datetest);
				$categories = $product->get_category_ids();
                if( ! in_array( WT_REVIEWS_EXTRA_ID, $categories ) ){
                    $trips[] = [
                        'product_id' => $product_id,
                        'product_name' => $order_item->get_name(),
						'end_date' => $endd
                    ];
                }

            }
        }
    }
	$datejour = strtotime(date("d/m/Y"));
	$date7 = strtotime("+1 week");
	
	
?>

<div>

    <?php if( ! empty( $trips ) )  :  ?>
	<div id="wizard" class="step-app">
        <ul class="step-steps">
            <li data-step-target="step1">
                <?php echo __('Trip review', 'wetreep-custom-reviews'); ?>
            </li>
            <li data-step-target="step2">
                <?php echo __('People review', 'wetreep-custom-reviews'); ?>
            </li>
        </ul>
        <div class="step-content">
            <div class="step-tab-panel" data-step="step1">
                <div class="step-title" >
                    <h3><?php echo __('Review trips  (Step 1/2)', 'wetreep-custom-reviews') ?></h3>
                </div>
                <div class="global-reviews-trip-list" id="accordionGroup" >
                    <?php
						 
                        foreach( $trips as $trip ){
							if($datejour == $trip["end_date"] && $trip["end_date"] < $date7)
							{
								// update_post_meta( $trip['product_id'], '_wt_review_users', array() );
								
								$review_users = ! empty(get_post_meta( $trip['product_id'], '_wt_review_users', true )) ? get_post_meta( $trip['product_id'], '_wt_review_users', true ) : [];

								$already_reviewed = false;

								if( in_array( get_current_user_id(), $review_users ) ){
									$already_reviewed = true;
								}

								echo '<h4>' . $trip['product_name'] . '</h4>';
								echo '<div class="trip-item">';
								if( ! $already_reviewed ){
									echo '<p>' . __('Review for the trip ', 'wetreep-custom-reviews') . ' "' . $trip['product_name'] . '"</p>';
									echo '<div class="trip-item-content">';
										echo '<div class="trip-form-container">';
											echo '<textarea name="trip-review" class="cm-textarea textrarea-' . $trip['product_id'] . ' " id="trip-review-textarea" rows="10"></textarea>';
											echo '<div class="trip-item-review-rating">';
												echo '<p>' . __('Your rating', 'wetreep-custom-reviews') . '</p>';
											echo '<div class="global-review-rating"></div>';
											echo '</div>';
									echo '</div>';
								} else {
									echo '<div class="trip-item-content">';
									echo '<div class="trip-form-container">';
									echo '<div class="already-reviewed" >';
									echo '<p>' . __("You've already reviewed this trip", 'wetreep-custom-reviews') . '</p>';
									echo '</div>';
									echo '</div>';
								}
								echo '<input type="hidden" name="trip-id" id="tripid" value="' . $trip['product_id'] . '">';
								echo '<input type="hidden" name="trip-rating" id="global_trip_rating" class="global_trip_rating" />';
								if( ! $already_reviewed ){
									echo '<button class="btn btn-primary btn-frontend gl-button reviews-next" data-trip-id=" ' . $trip['product_id'] . '" id="trip-review-submit" >' . __('Submit trip review', 'wetreep-custom-reviews') . '</button>';
								} else {
									echo '<button class="btn btn-primary btn-frontend gl-button reviews-next-without-submit" data-trip-id="' . $trip['product_id'] . '" >' . __('Review participants', 'wetreep-custom-reviews') . '</button>';
								}
								echo '</div>';
								echo '</div>';
							
							}
							else echo __('No trip to review', 'wetreep-custom-reviews');
                        }
                    ?>
                </div>
            </div>
            <div class="step-tab-panel participants-tab" data-step="step2" >
                <div class="participants-tab-header" >
                    <div>
                        <h3><?php echo __('Review participants (Step 2/2)', 'wetreep-custom-reviews')  ?></h3>
                        <div>
                            <p class="rating-info" ><?php echo __( "With Excellent = 5/5, Good 4/5, Correct 3/5, Poor 2/5, Bad 1/5", "wetreep-custom-reviews" ); ?></p>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-frontend gl-button reviews-prev" ><?php echo __('Previous', 'wetreep-custom-reviews'); ?></button>
                </div>
                <div class="participants-list"></div>
                <div class="loading-rec">
                    <div class="loading-rec-inner">
                        <div class="loading-rec-img">
                            <img src="<?php echo get_stylesheet_directory_uri() . '/assets/img/loading.gif' ?>" alt="loading" />
                        </div>
                        <div class="loading-rec-text">
                            <p><?php echo __( 'Loading participants...', 'wetreep-custom-reviews' ) ?></p>
                        </div>
                    </div>
                </div>
                <div class="done-container" >
                    <button class="btn btn-primary btn-frontend gl-button done-button"><?php echo __('Validate', 'wetreep-custom-reviews'); ?></button>
                </div>
            </div>
        </div>
        <div class="step-footer">
            <button data-step-action="prev" class="step-btn"><?php echo __('Previous', 'wetreep-custom-reviews'); ?></button>
            <button data-step-action="next" class="step-btn"><?php echo __('Next', 'wetreep-custom-reviews'); ?></button>
            <button data-step-action="finish" class="step-btn"><?php echo __('Finish', 'wetreep-custom-reviews'); ?></button>
        </div>
    </div>
    <?php else : ?>
        <p><?php echo __('No trip to review', 'wetreep-custom-reviews') ?></p>
    <?php endif; ?>
</div>