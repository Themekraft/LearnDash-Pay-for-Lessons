<?php
/*
* Plugin Name: Lesson Buy
* Plugin URI: https://oxygensoft.net/
* Description: Lesson Buy is custom plugin to work with learn dash
* Author: Faizan Gill
* Version: 1.0.6
* Text Domain: learndash_pfl
* Domain Path: /languages
* Author URI: https://oxygensoft.net/
*/


if ( in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins'))) )
{

	function register_my_session()
	{
		if( !session_id() ) {
			session_start();
		}
	}
	add_action('init', 'register_my_session');

	function set_lesson_access($lesson_ids_ar, $user_id)
	{
	    foreach ($lesson_ids_ar as $lesson_id) {
	        $user_ids = get_post_meta($lesson_id, "access_user_id", true);

	        $user_ids = unserialize($user_ids);

	        if (!is_array($user_ids)) {
	            $user_ids = [];
	        }
	        $user_ids[] = $user_id;

	        $user_ids = array_unique(array_merge($user_ids, $user_ids));

	        update_post_meta(
	            $lesson_id,
	            "access_user_id",
	            maybe_serialize($user_ids)
	        );
	    }
	}


	include_once("woocommerce_fields.php");	
	include_once("learndash_fields.php");
	include_once("buy_product_system.php");


	function get_session()
	{	
		if ( ! session_id() ) {
	        session_start();
	    }
	    if (isset($_SESSION["temp_user"]["user_id"]) and $_SESSION["temp_user"]["user_id"]>0){
	        return $_SESSION["temp_user"]["user_id"];
	    } else {
	    	
	        $_SESSION["temp_user"]["user_id"] = time() . rand();
	        return $_SESSION["temp_user"]["user_id"];
	    }
	}

	function get_course_lessons()
	{
		$courses = $_REQUEST['courses'];
	    $args = array(
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
		if (isset($_REQUEST['productID']) && $_REQUEST['productID'] != '') 
		{
			$lesson_idss = unserialize(get_post_meta( $_REQUEST['productID'], '_lesson_id' , true));
			
		}
		$options = '';
		$options .= '<option value="">'.__("Select lesson", "learndash_pfl").'</option>';
		$the_query = new WP_Query( $args );
		if($the_query->have_posts() ) : 
		    while ( $the_query->have_posts() ) : 
		       $the_query->the_post();
		       $id  	 =	get_the_ID();
		       $options .= '<option value="'.$id.'" '.(count($lesson_idss) > 0 && in_array( $id, $lesson_idss ) ? "selected" : "" ).'>'.__(get_the_title(), "learndash_pfl").'</option>';
		    endwhile; 
		    wp_reset_postdata(); 
		else: 
		endif;

		
		echo $options;
		die();
	}

	add_action('wp_ajax_nopriv_get_course_lessons', 'get_course_lessons');
	add_action('wp_ajax_get_course_lessons', 'get_course_lessons');



	function enqueue_select2_jquery() {
	    wp_register_style( 'select2css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.css', false, '1.0', 'all' );
	    wp_register_script( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.js', array( 'jquery' ), '1.0', true );
	    wp_enqueue_style( 'select2css' );
	    wp_enqueue_script( 'select2' );
	}
	add_action( 'admin_enqueue_scripts', 'enqueue_select2_jquery' );

	function select2jquery_inline() {
	    ?>
		<style type="text/css">
		.select2-container {margin: 0 2px 0 2px;}
		.tablenav.top #doaction, #doaction2, #post-query-submit {margin: 0px 4px 0 4px;}
		</style>        
		<script type='text/javascript'>
		jQuery(document).ready(function ($) {
		   jQuery(".select2").select2(); 
		   jQuery( document.body ).on( "click", function() {
		        jQuery("select").select2(); 
		     });
		});
		</script>   
	    <?php
	}
	add_action( 'admin_head', 'select2jquery_inline' );


	include_once "debug.php";
}
else
{
	
	function general_admin_notice(){
	    global $pagenow;
	    
         echo '<div class="notice notice-warning is-dismissible">
	             <p>'.__("Buy lesson plugin required woocommerce plugin to activate", "learndash_pfl").'</p>
	           </div>';
	}
	add_action('admin_notices', 'general_admin_notice');
}

?>