<?php

function woocommerce_wp_select_multiple( $field ) {
    global $thepostid, $post, $woocommerce;

    $thepostid              = empty( $thepostid ) ? $post->ID : $thepostid;
    $field['class']         = isset( $field['class'] ) ? $field['class'] : 'select short';
    $field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
    $field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
    $field['value']         = isset( $field['value'] ) ? $field['value'] : ( get_post_meta( $thepostid, $field['id'], true ) ? unserialize(get_post_meta( $thepostid, $field['id'], true )) : array() );

    echo '<p class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '"><label for="' . esc_attr( $field['id'] ) . '">' .__(wp_kses_post( $field['label'] ), "learndash_pfl") . '</label><select id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['name'] ) . '" class="' . esc_attr( $field['class'] ) . '" multiple="multiple">';

    foreach ( $field['options'] as $key => $value ) {
        echo '<option value="' . esc_attr( $key ) . '" ' . ( in_array( $key, $field['value'] ) ? 'selected="selected"' : '' ) . '>' . __(esc_html( $value ), "learndash_pfl") . '</option>';
    }

    echo '</select> ';

    if ( ! empty( $field['description'] ) ) {

        if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
            echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . esc_url( WC()->plugin_url() ) . '/assets/images/help.png" height="16" width="16" />';
        } else {
            echo '<span class="description">' .__(wp_kses_post($field['description']), "learndash_pfl") . '</span>';
        }

    }
    echo '</p>';
}

add_action( 'plugins_loaded', 'wcpt_register_lesson_type' );

function wcpt_register_lesson_type () {

	class WC_Product_Lesson_Type extends WC_Product {

		public function __construct( $product ) {
			$this->product_type = 'lesson_type'; // name of your custom product type
			parent::__construct( $product );
			// add additional functions here
		}
    }
}

add_filter( 'product_type_selector', 'wcpt_add_lesson_type_type' );

function wcpt_add_lesson_type_type ( $type ) {
	// Key should be exactly the same as in the class product_type
	$type[ 'lesson_type' ] = __( 'Lesson', 'learndash_pfl' );
	return $type;
}
add_filter( 'woocommerce_product_data_tabs', 'lesson_type_tab' );

function lesson_type_tab( $tabs) {
	// Key should be exactly the same as in the class product_type
	$tabs['lesson_type'] = array(
		'label'	 => __( 'Lesson', 'learndash_pfl' ),
		'target' => 'lesson_type_options',
		'class'  => ('show_if_lesson_type'),
	);
	return $tabs;
}


add_action( 'woocommerce_product_data_panels', 'wcpt_lesson_type_options_product_tab_content' );

function wcpt_lesson_type_options_product_tab_content() {
	// Dont forget to change the id in the div with your target of your product tab
	

	?><div id='lesson_type_options' class='panel woocommerce_options_panel'><?php
		?>
		<div class='options_group'>

		<?php
			include_once("script_for_ajax_dropdown.php");

			woocommerce_wp_checkbox( array(
				'id' 	=> '_enable_lesson_type',
				'label' => __( 'Enable As Lesson Product', 'learndash_pfl' ),
			) );

			woocommerce_wp_text_input( array(
	       		'id'          => '_regular_price',
	       		'label'       => __( 'Regular price (₨)', 'learndash_pfl' ),
	       		'placeholder' => '',
	       		'desc_tip'    => 'true',
	       		'description' => __( 'Enter Lesson Regular Price.', 'learndash_pfl' ),
	        ));
	        woocommerce_wp_text_input( array(
	       		'id'          => '_sale_price',
	       		'label'       => __( 'Sale price (₨)', 'learndash_pfl' ),
	       		'placeholder' => '',
	       		'desc_tip'    => 'true',
	       		'description' => __( 'Enter Lesson Sale Price.', 'learndash_pfl' ),
	        ));

			woocommerce_wp_checkbox( array(
				'id' 	=> '_all_lessons',
				'label' => __( 'All Lessons', 'learndash_pfl' ),
			));

	        $args = array(
			    'post_type'=> 'sfwd-courses',
			    'order'    => 'ASC'
			); 	

			$options[''] = __( 'Select Course', 'learndash_pfl' );
			$the_query = new WP_Query( $args );
			if($the_query->have_posts() ) : 
			    while ( $the_query->have_posts() ) : 
			       $the_query->the_post();
			       $id  		=	get_the_ID(); 
			       $options[$id] = __(get_the_title(), 'learndash_pfl' );
			    endwhile; 
			    wp_reset_postdata(); 
			else: 
			endif;

			echo '<div class="options_group">';

		    /*woocommerce_wp_select( array( // Text Field type
		        'id'          => '_course_id',
		        'label'       => __( 'Lesson', 'learndash_pfl' ),
		        'description' => __( 'Attach Lesson.', 'learndash_pfl' ),
		        'desc_tip'    => true,
		        'options'     =>  $options
		    ));*/

		    woocommerce_wp_select_multiple( array(
			    'id'          => '_course_id',
		        'label'       => __( 'Courses', 'learndash_pfl' ),
		        'description' => __( 'Attach Courses.', 'learndash_pfl' ),
			    'name' 		  => '_course_id[]',
			    'class' 	  => 'lesson_form_select',
			    'options' 	  =>  $options
			));

		    echo '</div>';

		    echo '<div class="options_group">';

		    woocommerce_wp_select_multiple( array(
			    'id'          => '_lesson_id',
		        'label'       => __( 'Lesson', 'learndash_pfl' ),
		        'description' => __( 'Attach Lesson.', 'learndash_pfl' ),
			    'name'		  => '_lesson_id[]',
			    'class' 	  => 'lesson_form_select',
			    //'options' 	  => $lesson_options
			    'options'   =>  array(__("Select Course First", "learndash_pfl"))
			));

		    echo '</div>';

		?></div>
	</div><?php
}

function set_product_id($lesson_id,$post_id)
{
    $product_ids   = get_post_meta(
                    $lesson_id,
                    "product_ids",
                    true);

    $product_ids   = unserialize($product_ids);   

    if(!is_array($product_ids))
    {
        $product_ids = array();    
    }
    $product_ids[] =   $post_id;

    $product_ids   =   array_unique(array_merge($product_ids, $product_ids) );

    update_post_meta(
                    $lesson_id,"product_ids",
                    maybe_serialize($product_ids)
                    );
}



add_action( 'woocommerce_process_product_meta', 'save_lesson_type_options_field' );

function save_lesson_type_options_field( $post_id ) {

	$enable_lesson_type = isset( $_POST['_enable_lesson_type'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, '_enable_lesson_type', $enable_lesson_type );

	if ( isset( $_POST['_regular_price'] ) ) :
		update_post_meta( $post_id, '_regular_price', sanitize_text_field( $_POST['_regular_price'] ) );
	endif;

	if ( isset( $_POST['_sale_price'] ) ) :
		update_post_meta($post_id,'_sale_price',sanitize_text_field($_POST['_sale_price']));
	endif;

	if ( isset( $_POST['_course_id'] ) ) :
		update_post_meta($post_id,'_course_id',maybe_serialize($_POST['_course_id']));
	endif;

	if ( isset( $_POST['_lesson_id'] ) ) :
		update_post_meta($post_id,'_lesson_id',maybe_serialize($_POST['_lesson_id']));
		
		update_post_meta("60", "product_id", "27");

		foreach($_POST['_lesson_id'] as $lesson_id) {
			set_product_id($lesson_id, $post_id);
			update_post_meta($lesson_id, "_ld_lesson_active", "1");
		}
	endif;
}


/*function student_post_redirect() {
    global $post;
    if (
        // check if we're about to show a Student post ...
        'sfwd-lessons' === $post->post_type
        && (
            
            ! is_user_logged_in()
            || ! ($user = wp_get_current_user())
            || $post->ID !== get_user_meta($user->ID, 'student_post', true)
        )
    ) {
        //wp_redirect(home_url());
        echo "=================";
        exit();
    }
} // function student_post_redirect
add_action('template_redirect', 'student_post_redirect');*/

function first_lesson_fn($lesson_id) 
{
	$first_lesson = false;
	$courses = get_post_meta($lesson_id,'course_id');
	if (!empty($courses)) 
	{
    	if(is_array($courses) && count($courses) > 0)
		{
			$course_id = $courses[0];
			$course_meta = get_post_meta($course_id,'ld_course_steps',true);
			$lesson_ar = (isset($course_meta['steps']['h']['sfwd-lessons']) ? $course_meta['steps']['h']['sfwd-lessons'] : array());
			if (isset($lesson_ar) && is_array($lesson_ar) && count($lesson_ar) > 0 ) 
			{
				if (array_key_first($lesson_ar) == $lesson_id) {
					$first_lesson = true;
				}
			}
		}
	}
	return $first_lesson;
}

function lesson__add_to_content( $content ) {

	global $post;

    if( is_single() && 'sfwd-lessons' === $post->post_type  ) {
    	$my_id = get_the_ID();

    	$ld_lesson_active = get_post_meta($my_id, '_ld_lesson_active',true);
    	if($ld_lesson_active=="1")
    	{
			$product_ids   = get_post_meta($my_id, "product_ids", true);
			$product_ids   = unserialize($product_ids);

    		if(is_user_logged_in())
    		{
    		
    			$user_id=get_current_user_id();

    			$temp_user = get_session();

    			$post_meta = get_post_meta($my_id,'access_user_id',true);

				$post_meta = @unserialize($post_meta);
	
				if(@in_array($temp_user,$post_meta))
	    		{
	    			set_lesson_access(array($my_id),$user_id);
		    	}	
	    		
	    		if (empty($product_ids)) 
	    		{
		    		$content = '<div class="ld-alert ld-alert-warning">
						   <div class="ld-alert-content">
						      <div class="ld-alert-icon ld-icon ld-icon-alert"></div>
						      <div class="ld-alert-messages">
						        '.__( 'This is paid content you need to contact admin regard buy this product.', 'learndash_pfl' ).'			
						      </div>
						   </div>
						</div>';
	    		} else {
					$post_meta = get_post_meta($my_id, 'access_user_id',true);
					$post_meta = @unserialize($post_meta);
					if(!is_array($post_meta) || !in_array($user_id,$post_meta))
		    		{
		    			if (count($product_ids) == 1) {
		    				$my_id = $product_ids[0];
		    				$permalink = get_permalink($my_id);
		    				//$content = 'Please buy this lessson <a href="'.$permalink.'" target="_blank">now</a>';
		    				$content = '<div class="ld-alert ld-alert-warning">
						   <div class="ld-alert-content">
						      <div class="ld-alert-icon ld-icon ld-icon-alert"></div>
						      <div class="ld-alert-messages">'.__( 'Please buy this lessson', 'learndash_pfl' ).' <a href="'.$permalink.'" target="_blank">'.__( 'Buy Now', 'learndash_pfl' ).'</a>			
						      </div>
						   </div>
						</div>';
		    			}
		    			else
		    			{
		    				$products = '';
		    				foreach($product_ids as $val22)
		    				{
			    				$my_id = $val22;
			    				$permalink = get_permalink($my_id);
			    				$products .= ' <a href="'.$permalink.'"  target="_blank">'.__(get_the_title($my_id), "learndash_pfl").'</a>  &nbsp;';
		    				}

		    				$content = '<div class="ld-alert ld-alert-warning">
						   <div class="ld-alert-content">
						      <div class="ld-alert-icon ld-icon ld-icon-alert"></div>
						      <div class="ld-alert-messages">'.__( 'To buy this lesson buy following any product.', 'learndash_pfl' ).' &nbsp;'.$products.'
						      </div>
						   </div>
						</div>';
		    			}
		    		}
		    		else 
		    		{
			    		$courses = get_post_meta($my_id,'course_id');
				    	if (!empty($courses)) 
				    	{
					    	if(is_array($courses) && count($courses) > 0)
				    		{
				    			$course_id = $courses[0];
				    			$course_meta = get_post_meta($course_id,'ld_course_steps',true);
				    			$lesson_ar = (isset($course_meta['steps']['h']['sfwd-lessons']) ? $course_meta['steps']['h']['sfwd-lessons'] : array());

				    			if (isset($lesson_ar) && is_array($lesson_ar) && count($lesson_ar) > 0 ) 
				    			{
			    					$page_data = array();
				    				foreach ($lesson_ar as $key => $value) 
				    				{
				    					$posts_meta	= get_post_meta($key, 'access_user_id',true);
										$posts_meta	= unserialize($posts_meta);
										
										if(!is_array($posts_meta) || !in_array($user_id,$posts_meta))
		    							{
		    								$ld_lesson_active = get_post_meta($key, '_ld_lesson_active',true);
									    	if($ld_lesson_active=="1")
									    	{
	    										$page_data[] = $key;
									    	}
		    							} 
		    							else if ($key == $my_id) {
		    								break;
		    							}
				    				}
				    				if (count($page_data) > 0) {
				    					$permalink = get_permalink($page_data[0]);

					    				$content = '<div class="ld-alert ld-alert-warning">
													   <div class="ld-alert-content">
													      <div class="ld-alert-icon ld-icon ld-icon-alert"></div>
													      <div class="ld-alert-messages">
													      '.__( 'Plz buy previous lessons first. You are redirecting to', 'learndash_pfl' ).'
													       "'.__(get_the_title($page_data[0]), "learndash_pfl").'" page.
													      </div>
													   </div>
													</div>
													<script type="text/javascript">
														setTimeout(function(){ 
															window.location.href="'.$permalink.'"; 
														}, 3000);
													</script>';
				    				}
				    			}
				    		}
				    	}
		    		}
	    		}
    		}
    		else
    		{

    			$temp_user = get_session();

    			$post_meta = get_post_meta($my_id,'access_user_id',true);

				$post_meta = @unserialize($post_meta);
				
				if(@in_array($temp_user,$post_meta)) {
		    	}
    			
    			else if(first_lesson_fn($my_id))
    			{
    				$product_ids   = get_post_meta($my_id, "product_ids", true);
		    		$product_ids   = unserialize($product_ids);
		    		if (empty($product_ids))
		    		{
		    			$content = '<div class="ld-alert ld-alert-warning">
						   <div class="ld-alert-content">
						      <div class="ld-alert-icon ld-icon ld-icon-alert"></div>
						      <div class="ld-alert-messages">
						      	'.__( 'This is paid content you need to contact admin regard buy this product.', 'learndash_pfl' ).'
						      </div>
						   </div>
						</div>';
		    		} 
		    		else {
						$my_id = $product_ids[0];
	    				$permalink = get_permalink($my_id);
	    				//$content = 'Please buy this lessson <a href="'.$permalink.'" target="_blank">now</a>';
	    				$content = '<div class="ld-alert ld-alert-warning">
							   <div class="ld-alert-content">
							      <div class="ld-alert-icon ld-icon ld-icon-alert"></div>
							      <div class="ld-alert-messages">'.__( 'Please buy this lessson', 'learndash_pfl' ).' <a href="'.$permalink.'" target="_blank">'.__( 'Buy Now', 'learndash_pfl' ).'</a>			
							      </div>
							   </div>
							</div>';
		    		}
    			}
    			else
    			{
    				$return_url = esc_url( home_url( '/login/' ) );	
    				$content = '<div class="ld-alert ld-alert-warning">
						   <div class="ld-alert-content">
						      <div class="ld-alert-icon ld-icon ld-icon-alert"></div>
						      <div class="ld-alert-messages">
						        Please <a href="'.$return_url.'" target="_blank">'.__( 'login', 'learndash_pfl' ).'</a>
						        '.__( 'first to access this content.', 'learndash_pfl' ).'
						      </div>
						   </div>
						</div>';
    			}
    		}
    	}

    }

    return $content;
}
add_filter('the_content', 'lesson__add_to_content');

?>