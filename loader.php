<?php
/*
* Plugin Name: LearnDash Pay for Lesson
* Plugin URI: https://themekraft.com
* Description: LearnDash Pay for Lesson enables you to sell LearnDash Lessons using Woocommerce.
* Author: Themekraft
* Version: 1.0.2
* Text Domain: learndash_pfl
* Domain Path: /languages
* Author URI: https://themekraft.com/
*/

if ( ! function_exists( 'dpflww_fs' ) ) {
    // Create a helper function for easy SDK access.
    function dpflww_fs() {
        global $dpflww_fs;

        if ( ! isset( $dpflww_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/includes/resources/freemius/start.php';

            $dpflww_fs = fs_dynamic_init( array(
                'id'                  => '9380',
                'slug'                => 'learn-dash-pay-for-lessons-with-woocommerce',
                'premium_slug'        => 'Learn-dash-pay-for-lessons-with-wooCommerce-premium',
                'type'                => 'plugin',
                'public_key'          => 'pk_b9fecc8926d3242517fbefb590a9b',
                'is_premium'          => true,
                'is_premium_only'     => true,
                'has_addons'          => false,
                'has_paid_plans'      => true,
                'trial'               => array(
                    'days'               => 14,
                    'is_require_payment' => true,
                ),
                'menu'                => array(
                    'first-path'     => 'plugins.php',
                    'support'        => false,
                ),
            ) );
        }

        return $dpflww_fs;
    }

    // Init Freemius.
    dpflww_fs();
    // Signal that SDK was initiated.
    do_action( 'dpflww_fs_loaded' );
} else{
	die;
}

if ( in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins') ) ) && dpflww_fs()->is_paying() ) {

	function set_lesson_access( $lesson_ids_ar, $user_id ) {
	    foreach ( $lesson_ids_ar as $lesson_id ) {
	        $user_ids = get_post_meta($lesson_id, "access_user_id", true);
	        $user_ids = unserialize( $user_ids );
	        if ( ! is_array( $user_ids ) ) {
	            $user_ids = [];
	        }
	        $user_ids[] = $user_id;
	        $user_ids = array_unique(array_merge($user_ids, $user_ids));
	        update_post_meta( $lesson_id, "access_user_id", maybe_serialize( $user_ids ) );
	    }
	}


	include_once 'includes/woocommerce-fields.php';	
	include_once 'includes/learndash-fields.php';
	include_once 'includes/product-integration.php';


	add_action('wp_ajax_nopriv_get_course_lessons', 'get_course_lessons');
	add_action('wp_ajax_get_course_lessons', 'get_course_lessons');
	function get_course_lessons() {
		$courses = isset( $_REQUEST['courses'] ) ? $_REQUEST['courses'] : '';
	    $args = array(
			'posts_per_page' => '-1',
		    'post_type'=> 'sfwd-lessons',
		    'order'    => 'ASC',
		    'meta_key' => 'course_id',
		    'meta_query' => array(
	            array(
	                'key'     => 'course_id',
	                'value'   => $courses,
	                'compare' => 'IN',
	            ),
	    	),
		); 
		$lesson_idss = array();
		if ( isset( $_REQUEST['productID']) && $_REQUEST['productID'] != '' ) {
			$lesson_idss = unserialize( get_post_meta( $_REQUEST['productID'], '_lesson_id' , true ) );
			
		}
		$options = '';
		$options .= '<option value="">'.__("Select lesson", "learndash_pfl").'</option>';
		$the_query = new WP_Query( $args );
		if( $the_query->have_posts() ) : 
		    while ( $the_query->have_posts() ) : 
		       $the_query->the_post();
		       $id  	 =	get_the_ID();
		       $options .= '<option value="'.$id.'" '.( count( $lesson_idss ) > 0 && in_array( $id, $lesson_idss ) ? "selected" : "" ).'>'.__( get_the_title(), "learndash_pfl" ).'</option>';
		    endwhile; 
		    wp_reset_postdata(); 
		endif;
		echo $options;
		die();
	}

	add_action( 'admin_enqueue_scripts', 'enqueue_select2_jquery' );
	function enqueue_select2_jquery() {
        global $post, $pagenow;
        if ( $pagenow == 'post-new.php' || $pagenow == 'post.php' ) {
            if ( 'product' === $post->post_type ) {     
                wp_enqueue_style( 'select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), '4.1.0-rc.0');
                wp_enqueue_script( 'select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', 'jquery', '4.1.0-rc.0');
            }
        }
	}
	
	add_action( 'admin_head', 'select2jquery_inline' );
	function select2jquery_inline() {
		global $post,$pagenow;
        if ( $pagenow == 'post-new.php' || $pagenow == 'post.php' ) {
            if ( 'product' === $post->post_type ) {     
                echo  '<script type="text/javascript">jQuery(document).ready(function($){jQuery(".lesson_form_select").select2();});</script>' ;
            }
        }
	}
} else {
	
	function general_admin_notice() {
         echo '<div class="notice notice-warning is-dismissible">
	             <p>'.__("Buy lesson plugin required woocommerce plugin to activate", "learndash_pfl").'</p>
	           </div>';
	}
	add_action('admin_notices', 'general_admin_notice');
}

?>