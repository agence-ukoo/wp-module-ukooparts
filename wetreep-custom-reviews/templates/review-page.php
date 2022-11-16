<?php

$user_id = get_current_user_id();

$has_review = false;

$posted_reviews_args = array(
	'post_type' => 'wt_reviews',
	'posts_per_page' => -1,
	'post_parent' => 0,
	'meta_query' => array(
		array(
			'key' => 'to',
			'value' => $user_id,
			'compare' => '='
		)
	)
);

$reviews = new WP_Query($posted_reviews_args);

$received_reviews_args = array(
	'post_type' => 'wt_reviews',
	'posts_per_page' => -1,
	'post_parent' => 0,
	'meta_query' => array(
		array(
			'key' => 'from',
			'value' => $user_id,
			'compare' => '='
		)
	)
);

$received_reviews = new WP_Query($received_reviews_args);

$count_reviews = $reviews->found_posts;
$count_received_reviews = $received_reviews->found_posts;

if ($count_reviews > 0) {
	$has_review = true;
}



// get all previous orders of the user
$reviewed_products = array();

$order_args = array(
	'customer_id' => $user_id,
);

$orders = wc_get_orders( $order_args );

foreach( $orders as $order ){
	foreach( $order->get_items() as $item ){
		if( $item->get_product_id() != WT_REVIEWS_EXTRA_ID ){
			$reviewed_products[] = $item->get_product_id();
		}
	}
}

?>
<div class="archive author">
	<div class="container">
		<div class="author-reviews">
			<div class="author-reviews-inner">
				<div class="reviews-head-container">
					<div class="block-heading">
						<h3><?php echo sprintf(__('My reviews', 'golo'), $count_reviews); ?></h3>
					</div>
					<div>
						
						<?php 
						
						?>
						<button class="button" id="add-new-global-review-button"><?php echo __('Review trips and participants', 'golo') ?></button>
						<?php 
						?>
					</div>
				</div>
				<div class="my-reviews-content-container">
					<div class="participants-reviews">
						<h3><?php echo __('Participants reviews', 'wetreep-custom-reviews'); ?></h3>
						<div class="block-heading">
						<h3><?php echo sprintf(__('Reviews (%1$s)', 'golo'), $count_reviews); ?></h3>
					</div>
						<div class="area-content">
							<?php if ($has_review) { ?>
								<ul class="listing-detail reviews-list custom-scrollbar">
									<?php
									while ($reviews->have_posts()) : $reviews->the_post();
										$get_review_from = get_post_meta(get_the_ID(), 'from', true);
										$user_from = get_user_by('id', $get_review_from);
										$user_from_login = $user_from->user_login;
										$user_from_avatar_id = get_user_meta($get_review_from, 'author_avatar_image_id', true);
										$user_from_avatar_url = wp_get_attachment_url($user_from_avatar_id);
										$user_from_link = get_author_posts_url($get_review_from);
										$post_rating = get_post_meta(get_the_ID(), 'rating', true);

									?>
										<li class="author-review">
											<div class="entry-head">
												<div class="entry-avatar">
													<?php echo $user_from_avatar; ?>
													<figure>
														<?php
														if (!empty($user_from_avatar_url)) {
															echo '<img src="' . $user_from_avatar_url . '" alt="' . $user_from_login . '">';
														} else {
															echo '<img src="' . esc_url($author_avatar_url['url']) . '" alt="' . $user_from_login . '">';
														}
														?>
													</figure>
												</div>
												<div class="entry-info">
													<div class="entry-name">
														<h3 class="author-name">
															<a href="<?php echo esc_url($user_from_link); ?>">
																<?php the_author_meta('display_name', $get_review_from); ?>
															</a>
														</h3>
														<?php if ($post_rating > 0) : ?>
															<div class="read-only-rating"></div>
															<input type="hidden" id="review-page-id" value="<?php echo get_post_meta(get_the_ID(), 'rating', true); ?>">
														<?php endif; ?>
														<div class="review-sig-container">
															<i class="fas fa-bell"></i>
														</div>
													</div>
													<div class="review-date"><?php echo get_the_date(); ?></div>
												</div>
											</div>
											<div class="entry-comment">
												<p class="review-content">
													<?php the_content(); ?>
												</p>
											</div>
											<div>
												<?php
												$answer_args = array(
													'post_type' => 'wt_reviews',
													'post_parent' => get_the_ID(),
												);

												$answer_reviews = new WP_Query($answer_args); ?>

												<ul class="listing-detail reviews-list custom-scrollbar">
													<?php

													if ($answer_reviews->have_posts()) :
														while ($answer_reviews->have_posts()) : $answer_reviews->the_post();
															$get_answer_from = get_post_meta(get_the_ID(), 'from', true);
															$user_answer_from = get_user_by('id', $get_answer_from);
															$user_answer_from_login = $user_answer_from->user_login;
															$user_answer_from_avatar_Id = get_user_meta($get_answer_from, 'author_avatar_image_id', true);
															$author_answer_avatar_url = wp_get_attachment_url($user_answer_from_avatar_Id);
															$answer_author_url = get_author_posts_url($get_answer_from);
													?>
															<li class="author-review">
																<div class="entry-head">
																	<div class="entry-avatar">
																		<figure>
																			<?php
																			if (!empty($author_answer_avatar_url)) {
																				echo '<img src="' . $author_answer_avatar_url . '" alt="' . $user_answer_from_login . '">';
																			} else {
																				echo '<img src="' . esc_url($author_avatar_url['url']) . '" alt="' . $user_from_login . '">';
																			}
																			?>
																		</figure>
																	</div>
																	<div class="entry-info">
																		<div class="entry-name">
																			<h3 class="author-name">
																				<a href="<?php echo esc_url($answer_author_url) ?>"><?php echo the_author_meta('display_name', $get_answer_from) ?></a>
																			</h3>
																		</div>
																		<div class="review-date"><?php echo get_the_date(); ?></div>
																	</div>
																</div>
																<div class="entry-comment">
																	<p class="review-content">
																		<?php echo get_the_content(); ?>
																	</p>
																</div>
															</li>
														<?php
														endwhile;
														wp_reset_postdata();
													else :
														?>
														<?php
														if (get_current_user_id() == $user_id) :
														?>
															<div class="asnwer-form-container">
																<form class="review-answer-form" action="">
																	<div class="form-group textarea-group">
																		<label for="wt_user_review_answer"><?php esc_html_e("Your answer", 'golo'); ?></label>
																		<textarea name="wt_user_review_answer" id="user-review-answer" class="review-textarea" cols="60" rows="6"></textarea>
																	</div>
																	<div>
																		<input type="hidden" name="wt_reviews_answer_from" value="<?php echo $user_id ?>" />
																		<input type="hidden" name="wt_reviews_answer_review_id" value="<?php echo get_the_ID(); ?>">
																		<?php wp_nonce_field('wt_reviews_answer_nonce_action', 'wt_reviews_answer_nonce_field'); ?>
																		<button type="submit" class="gl-button btn button answer-button " id="review-answer-submit"><?php esc_html_e('Submit answer', 'golo'); ?></button>
																	</div>
																</form>
															</div>
															<div id="submit-answer-message"></div>

													<?php
														endif;
													endif;
													?>
												</ul>
											</div>

										</li>
									<?php
									endwhile;
									wp_reset_postdata();
									?>
								</ul>
							<?php } else { ?>
								<span class="no-item"><?php echo __('No recent reviews', 'wetreep-custom-reviews'); ?></span>
							<?php } ?>
						</div>
					</div>
					<div class="trips-reviews" >
						<h3><?php echo __('Trips reviews', 'wetreep-custom-reviews'); ?></h3>
						<?php do_action( 'wt_view_user_reviews_profile', $user_id, $reviewed_products ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="global-review-dialog">
		<?php
		wp_enqueue_script('wt-reviews-wizard');
		wp_enqueue_style('wt-reviews-wizard-css');
		wp_enqueue_script('wt-reviews-wizard-theme-css');
		wp_enqueue_script('wt-reviews-wizard-custom-script');
		ob_start();
		include_once(WT_REVIEWS_PATH  . '/views/wt_global_review_dialog.php');
		echo ob_get_clean();
		?>
	</div>
	<!-- reveived reviews -->
</div>