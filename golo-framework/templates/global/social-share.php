<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$social_sharing   = golo_get_option( 'social_sharing', array() );
$sharing_facebook = $sharing_twitter = $sharing_google = $sharing_linkedin = $sharing_tumblr = $sharing_pinterest = '1';

if(is_array( $social_sharing ) && count( $social_sharing ) > 0) {
    $sharing_facebook  = in_array( 'facebook', $social_sharing );
    $sharing_twitter   = in_array( 'twitter', $social_sharing );
    $sharing_google    = in_array( 'google', $social_sharing );
    $sharing_linkedin  = in_array( 'linkedin', $social_sharing );
    $sharing_tumblr    = in_array( 'tumblr', $social_sharing );
    $sharing_pinterest = in_array( 'pinterest', $social_sharing );
}

if ( !$sharing_facebook && !$sharing_twitter && !$sharing_google && !$sharing_linkedin && !$sharing_tumblr && !$sharing_pinterest ) {
    return;
}

?>
<div class="social-share">
    <div class="list-social-icon">
        <?php if ( $sharing_facebook == 1 ) :?>
            <a class="facebook" onclick="window.open('https://www.facebook.com/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>','sharer', 'toolbar=0,status=0');" href="javascript:void(0)">
                <i class="fab fa-facebook-f"></i>
            </a>
        <?php endif; ?>

        <?php if ( $sharing_twitter == 1 ) : ?>
            <a class="twitter" onclick="popUp=window.open('https://twitter.com/share?url=<?php echo urlencode( get_permalink()); ?>','sharer','scrollbars=yes');popUp.focus();return false;" href="javascript:void(0)">
                <i class="fab fa-twitter"></i>
            </a>
        <?php endif; ?>

        <?php if ( $sharing_google == 1 ) : ?>
            <a class="google-plus" href="javascript:void(0)" onclick="popUp=window.open('https://plus.google.com/share?url=<?php echo urlencode( get_permalink()); ?>','sharer','scrollbars=yes');popUp.focus();return false;">
                <i class="fab fa-google-plus-g"></i>
            </a>
        <?php endif; ?>

        <?php if ( $sharing_linkedin == 1 ): ?>
            <a class="linkedin" onclick="popUp=window.open('http://linkedin.com/shareArticle?mini=true&amp;url=<?php echo urlencode( get_permalink()); ?>&amp;title=<?php echo urlencode( get_the_title() ); ?>','sharer','scrollbars=yes');popUp.focus();return false;" href="javascript:void(0)">
                <i class="fab fa-linkedin-in"></i>
            </a>
        <?php endif; ?>

        <?php if ( $sharing_tumblr == 1 ) : ?>
            <a class="tumblr" onclick="popUp=window.open('http://www.tumblr.com/share/link?url=<?php echo urlencode( get_permalink()); ?>&amp;name=<?php echo urlencode( get_the_title()); ?>&amp;description=<?php echo urlencode( get_the_excerpt()  ); ?>','sharer','scrollbars=yes');popUp.focus();return false;" href="javascript:void(0)">
                <i class="fab fa-tumblr"></i>
            </a>
        <?php endif; ?>

        <?php if ( $sharing_pinterest == 1 ) : ?>
            <a class="pinterest" onclick="popUp=window.open('http://pinterest.com/pin/create/button/?url=<?php echo urlencode( get_permalink()); ?>&amp;description=<?php echo urlencode( get_the_title()); ?>&amp;media=<?php $arrImages = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); echo has_post_thumbnail() ? esc_attr( $arrImages[0] ) : ""; ?>','sharer','scrollbars=yes,width=800,height=400');popUp.focus();return false;" href="javascript:void(0)">
                <i class="fab fa-pinterest-p"></i>
            </a>
        <?php endif; ?>
    </div>
</div>