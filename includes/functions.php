<?php
/**
Header Output
**/
function wpdev_fbcomments_header() {
    // Get the options.
    $wpdevfb = get_option('wpdevelopers_facebook_comments_option_name');

    // Parse the options.
    $enable     = $wpdevfb['enable_comments_0'];
    $sdkhead    = $wpdevfb['add_facebook_sdk_to_head_1'];
    $appid      = $wpdevfb['facebook_app_id_2'];

    // Check if there's an app ID provided.
    if(!empty($enable) && !empty($appid)) {
        echo '<meta property="fb:app_id" content="'.$appid.'" />';

        // Check if we need to output the SDK.
        if(!empty($sdkhead)) {
            echo '
            <div id="fb-root"></div>
            <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId='.$appid.'&autoLogAppEvents=1"></script>';
        }
    }
}
add_action('wp_head', 'wpdev_fbcomments_header');

/**
Output Comments
**/
function wpdev_fbcomments() {
    // Get the options.
    $wpdevfb = get_option('wpdevelopers_facebook_comments_option_name');

    // Parse the options and set variables.
    $enable     = $wpdevfb['enable_comments_0'];
    $sdkhead    = $wpdevfb['add_facebook_sdk_to_head_1'];
    $appid      = $wpdevfb['facebook_app_id_2'];
    $num        = $wpdevfb['number_of_comments_5'];
    $url        = get_permalink();

    // Check if the comments are enabled.
    if(!empty($enable) && !empty($appid) && !empty($sdkhead)) {
        // Check if the $num variable is set.
        if(empty($num)) {
            $num = 10;
        }

        // Setup the output.
        $output = '<div class="fb-comments" data-href="'.$url.'" data-numposts="6"></div>';
    } else {
        $output = '';
    }

    // Return the output.
    return $output;
}
add_shortcode('wpdevfb', 'wpdev_fbcomments');
add_action('wpdevfb', 'wpdev_fbcomments');

/**
Comment Count Output
**/
function wpdev_fbcomments_count() {
    // Get the options.
    $wpdevfb = get_option('wpdevelopers_facebook_comments_option_name');

    // Parse the options and set variables.
    $enable     = $wpdevfb['enable_comments_0'];
    $sdkhead    = $wpdevfb['add_facebook_sdk_to_head_1'];
    $appid      = $wpdevfb['facebook_app_id_2'];
    $num        = $wpdevfb['number_of_comments_5'];
    $url        = get_permalink();

    // Check if we're enabled and have our variables.
    if(!empty($enable) && !empty($appid) && !empty($sdkhead)) {
        $output = '<div class="wpdevfb-comment-count"><span class="fb-comments-count" data-href="'.$url.'"></span> comments</div>';
    }
}
add_shortcode('wpdevfbcount', 'wpdev_fbcomments_count');
